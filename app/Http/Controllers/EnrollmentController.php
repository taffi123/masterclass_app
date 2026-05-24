<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\MasterClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function confirm(MasterClass $masterClass): View
    {
        $this->ensureVisitor();
        $this->validateBooking($masterClass);
        $masterClass->load(['creativityType', 'instructor'])->loadCount('enrollments');

        return view('enrollments.confirm', compact('masterClass'));
    }

    public function cancel(MasterClass $masterClass): RedirectResponse
    {
        $this->ensureVisitor();

        return redirect()
            ->route('types.show', $masterClass->creativityType)
            ->with('status', 'Запись отменена.');
    }

    public function store(MasterClass $masterClass): RedirectResponse
    {
        $this->ensureVisitor();
        $this->validateBooking($masterClass);

        $masterClass->enrollments()->create([
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('types.show', $masterClass->creativityType)
            ->with('status', 'Запись подтверждена.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $this->ensureVisitor();

        abort_unless($enrollment->user_id === Auth::id(), 403);

        $enrollment->delete();

        return redirect()
            ->route('home')
            ->with('status', 'Запись удалена.');
    }

    private function ensureVisitor(): void
    {
        abort_unless(Auth::check() && Auth::user()->isVisitor(), 403);
    }

    private function validateBooking(MasterClass $masterClass): void
    {
        $masterClass->loadCount('enrollments');

        if ($masterClass->hasStarted()) {
            throw ValidationException::withMessages([
                'booking' => 'Запись на этот мастер-класс уже недоступна.',
            ]);
        }

        if ($masterClass->isFull()) {
            throw ValidationException::withMessages([
                'booking' => 'Свободных мест больше нет.',
            ]);
        }

        $alreadyBooked = $masterClass->enrollments()
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyBooked) {
            throw ValidationException::withMessages([
                'booking' => 'Вы уже записаны на этот мастер-класс.',
            ]);
        }
    }
}
