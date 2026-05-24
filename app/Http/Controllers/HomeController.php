<?php

namespace App\Http\Controllers;

use App\Models\CreativityType;
use App\Models\MasterClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $types = CreativityType::query()
            ->withCount('masterClasses')
            ->orderBy('name')
            ->get();

        $myEnrollments = collect();

        if (Auth::check() && Auth::user()->isVisitor()) {
            $myEnrollments = Auth::user()->enrollments()
                ->with(['masterClass.creativityType', 'masterClass.instructor'])
                ->latest()
                ->get();
        }

        return view('home', compact('types', 'myEnrollments'));
    }

    public function category(CreativityType $type): View
    {
        $query = $type->masterClasses()
            ->with(['instructor'])
            ->withCount('enrollments')
            ->orderBy('class_date')
            ->orderBy('start_time');

        if (Auth::check() && Auth::user()->isVisitor()) {
            $query->withExists([
                'enrollments as is_booked' => fn (Builder $enrollments): Builder => $enrollments->where('user_id', Auth::id()),
            ]);
        }

        $masterClasses = $query->get()
            ->sortBy([
                fn ($masterClass): int => $masterClass->hasStarted() ? 1 : 0,
                fn ($masterClass): int => $masterClass->starts_at->getTimestamp(),
            ])
            ->values();

        return view('category', compact('type', 'masterClasses'));
    }
}
