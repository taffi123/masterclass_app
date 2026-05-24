<?php

namespace App\Http\Controllers;

use App\Models\CreativityType;
use App\Models\MasterClass;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InstructorMasterClassController extends Controller
{
    private const TIME_SLOTS = ['09:00', '11:00', '13:00', '15:00'];

    public function index(): View
    {
        $this->ensureInstructor();

        $masterClasses = Auth::user()->masterClasses()
            ->with(['creativityType'])
            ->withCount('enrollments')
            ->orderBy('class_date')
            ->orderBy('start_time')
            ->get();

        return view('instructor.dashboard', compact('masterClasses'));
    }

    public function create(Request $request): View
    {
        $this->ensureInstructor();

        $types = CreativityType::orderBy('name')->get();
        $selectedDate = $request->query('date', $request->query('class_date', old('class_date', now()->format('Y-m-d'))));

        if (Carbon::parse($selectedDate)->lt(now()->startOfDay())) {
            $selectedDate = now()->format('Y-m-d');
        }
        $disabledSlots = $this->disabledSlots($selectedDate);

        return view('instructor.create', compact('types', 'selectedDate', 'disabledSlots'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureInstructor();

        $data = $request->validate([
            'creativity_type_id' => ['required', 'exists:creativity_types,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'class_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', Rule::in(self::TIME_SLOTS)],
            'max_participants' => ['required', 'integer', 'min:1', 'max:30'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ], [
            'price.max' => 'Стоимость не должна быть больше 99 999 999.99.',
        ]);

        if ($this->isSlotInPast($data['class_date'], $data['start_time'])) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Нельзя выбрать уже прошедшее время.']);
        }

        if ($this->isSlotOccupied($data['class_date'], $data['start_time'])) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Этот слот уже занят. Выберите другое время.']);
        }

        $data['instructor_id'] = Auth::id();
        $data['end_time'] = Carbon::createFromFormat('H:i', $data['start_time'])->addHours(2)->format('H:i');

        MasterClass::create($data);

        return redirect()->route('cabinet.index')->with('status', 'Мастер-класс добавлен.');
    }

    public function show(MasterClass $masterClass): View
    {
        $this->ensureOwner($masterClass);

        $masterClass->load(['creativityType', 'enrollments.user']);
        $masterClass->loadCount('enrollments');

        return view('instructor.show', compact('masterClass'));
    }

    public function edit(MasterClass $masterClass): View
    {
        $this->ensureOwner($masterClass);

        return view('instructor.edit', compact('masterClass'));
    }

    public function update(Request $request, MasterClass $masterClass): RedirectResponse
    {
        $this->ensureOwner($masterClass);

        $data = $request->validate([
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ], [
            'price.max' => 'Стоимость не должна быть больше 99 999 999.99.',
        ]);

        $masterClass->update($data);

        return redirect()->route('cabinet.show', $masterClass)->with('status', 'Мастер-класс обновлен.');
    }

    public function destroy(MasterClass $masterClass): RedirectResponse
    {
        $this->ensureOwner($masterClass);

        $masterClass->delete();

        return redirect()->route('cabinet.index')->with('status', 'Мастер-класс удален.');
    }

    private function ensureInstructor(): void
    {
        abort_unless(Auth::check() && Auth::user()->isInstructor(), 403);
    }

    private function ensureOwner(MasterClass $masterClass): void
    {
        $this->ensureInstructor();
        abort_unless($masterClass->instructor_id === Auth::id(), 403);
    }

    private function isSlotOccupied(string $date, string $startTime): bool
    {
        return Auth::user()->masterClasses()
            ->whereDate('class_date', $date)
            ->where('start_time', $startTime)
            ->exists();
    }

    private function disabledSlots(string $date): array
    {
        $occupiedSlots = Auth::user()->masterClasses()
            ->whereDate('class_date', $date)
            ->pluck('start_time')
            ->map(fn ($value) => substr($value, 0, 5))
            ->all();

        $pastSlots = array_filter(
            self::TIME_SLOTS,
            fn (string $slot): bool => $this->isSlotInPast($date, $slot)
        );

        return array_values(array_unique([...$occupiedSlots, ...$pastSlots]));
    }

    private function isSlotInPast(string $date, string $startTime): bool
    {
        return Carbon::parse($date . ' ' . $startTime)->lessThanOrEqualTo(now());
    }
}
