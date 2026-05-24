<?php

namespace Tests\Feature;

use App\Models\CreativityType;
use App\Models\Enrollment;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterClassFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_creativity_types(): void
    {
        CreativityType::query()->create($this->typeData(['name' => 'Кулинария', 'slug' => 'cooking']));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Кулинария');
    }

    public function test_instructor_can_create_master_class(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $type = CreativityType::query()->create($this->typeData());

        $response = $this->actingAs($instructor)->post(route('cabinet.store'), [
            'creativity_type_id' => $type->id,
            'title' => 'Основы резьбы',
            'description' => 'Практический мастер-класс для начинающих.',
            'class_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '09:00',
            'max_participants' => 10,
            'price' => 1500,
        ]);

        $response->assertRedirect(route('cabinet.index'));
        $this->assertDatabaseHas('master_classes', [
            'title' => 'Основы резьбы',
            'instructor_id' => $instructor->id,
            'end_time' => '11:00',
        ]);
    }

    public function test_visitor_can_enroll_and_cancel_enrollment(): void
    {
        $visitor = User::factory()->create(['role' => 'visitor']);
        $masterClass = $this->createMasterClass();

        $this->actingAs($visitor)
            ->post(route('enrollments.store', $masterClass))
            ->assertRedirect(route('types.show', $masterClass->creativityType));

        $enrollment = Enrollment::query()->where('user_id', $visitor->id)->firstOrFail();
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $visitor->id,
            'master_class_id' => $masterClass->id,
        ]);

        $this->actingAs($visitor)
            ->delete(route('enrollments.destroy', $enrollment))
            ->assertRedirect(route('home'));

        $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
    }

    public function test_instructor_cannot_enroll_to_master_class(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $masterClass = $this->createMasterClass();

        $this->actingAs($instructor)
            ->post(route('enrollments.store', $masterClass))
            ->assertForbidden();
    }

    public function test_visitor_cannot_book_full_master_class(): void
    {
        $visitor = User::factory()->create(['role' => 'visitor']);
        $masterClass = $this->createMasterClass(['max_participants' => 1]);
        Enrollment::query()->create([
            'user_id' => User::factory()->create(['role' => 'visitor'])->id,
            'master_class_id' => $masterClass->id,
        ]);

        $this->actingAs($visitor)
            ->post(route('enrollments.store', $masterClass))
            ->assertSessionHasErrors('booking');
    }

    private function createMasterClass(array $overrides = []): MasterClass
    {
        $type = CreativityType::query()->create($this->typeData());
        $instructor = User::factory()->create(['role' => 'instructor']);

        return MasterClass::query()->create(array_merge([
            'creativity_type_id' => $type->id,
            'instructor_id' => $instructor->id,
            'title' => 'Мастер-класс',
            'description' => 'Описание мастер-класса.',
            'class_date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '11:00',
            'end_time' => '13:00',
            'max_participants' => 5,
            'price' => 1000,
        ], $overrides));
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    private function typeData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Творчество',
            'slug' => 'creative-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => 'Описание направления творчества.',
            'image_path' => 'image/test.jpg',
        ], $overrides);
    }
}
