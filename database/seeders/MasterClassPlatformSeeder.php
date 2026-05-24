<?php

namespace Database\Seeders;

use App\Models\CreativityType;
use App\Models\Enrollment;
use App\Models\MasterClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterClassPlatformSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::query()->updateOrCreate(
            ['email' => 'master@example.com'],
            [
                'name' => 'Марина Орлова',
                'phone' => '+7 912 345-67-89',
                'role' => 'instructor',
                'avatar' => 'driver1.png',
                'about' => 'Ведущая мастер-классов по кулинарии и архитектурному моделированию.',
                'password' => Hash::make('master123'),
            ]
        );

        $secondInstructor = User::query()->updateOrCreate(
            ['email' => 'wood@example.com'],
            [
                'name' => 'Илья Котов',
                'phone' => '+7 912 222-33-44',
                'role' => 'instructor',
                'avatar' => 'driver2.png',
                'about' => 'Специалист по резьбе по дереву и работе с натуральными материалами.',
                'password' => Hash::make('master123'),
            ]
        );

        $visitor = User::query()->updateOrCreate(
            ['email' => 'visitor@example.com'],
            [
                'name' => 'Анна Смирнова',
                'phone' => '+7 922 123-45-67',
                'role' => 'visitor',
                'avatar' => 'driver3.png',
                'about' => null,
                'password' => Hash::make('visitor123'),
            ]
        );

        $types = collect([
            [
                'name' => 'Архитектурное моделирование',
                'slug' => 'architectural-modeling',
                'description' => "Архитектурное моделирование — это изготовление моделей зданий, сооружений, исторических памятников, а также инженерных и фортификационных сооружений. Отличительной особенностью образовательной программы является то, что она значительно расширяет пространство для изучения народных традиций, дает начальные навыки деревообработки, формирует эстетический вкус у учащихся.\n\nДанная программа не имеет аналогов среди образовательных дополнительных программ, так как впервые для изготовления макетов применяются бамбуковые палочки, в качестве основного элемента конструкции, что позволяет значительно упростить технологию создания макета и обучить начальным навыкам деревообработки.\n\nНа занятиях учащиеся получают теоретические знания по древнерусской архитектуре и народным традициям, изучают краеведческий материал, применяют знания на практике, создавая исторические реконструкции зданий и сооружений.",
                'image_path' => 'image/architectural-model-pictures-cool-architectural-model-.jpg',
            ],
            [
                'name' => 'Кулинария',
                'slug' => 'kulinariya',
                'description' => "Кулинария – искусство приготовления пищи. Еда – это топливо, на котором работает организм, и знать об этом топливе, уметь грамотно его использовать должен любой человек. К сожалению, в большинстве случаев интерес к проблеме питания возникает с годами, когда большинство продуктов оказывается вредным для растущего организма.\n\nВеликие тайны кулинарии откроются перед теми, кто захочет научиться готовить по всем правилам, превращать сырые продукты во вкусную и полезную пищу. Умение хорошо, то есть правильно, вкусно и быстро, готовить является одним из условий счастливой, спокойной жизни.\n\n«В здоровом теле – здоровый дух!» - настроение, здоровье, готовность трудиться во многом зависит от питания и отдыха. Мы стремимся возродить традиции семейных праздников и здорового питания. Полученные знания помогут не только накормить семью, но и принять гостей.",
                'image_path' => 'image/steak-1024x678.jpg',
            ],
            [
                'name' => 'Резьба по дереву',
                'slug' => 'wood-carving',
                'description' => "Резьба по дереву - древнейший вид русского народного декоративного искусства. В нашей стране, богатой лесами, дерево всегда было одним из самых любимых материалов. Понимание его пластических качеств, красоты текстуры развивалось в творческом опыте многих поколений народных мастеров.\n\nВысокий уровень исполнительского мастерства, образная и поэтическая выразительность деревянных изделий всегда соединялись с утилитарным назначением вещей. Это во многом определяло и способы художественной обработки, и характер орнаментального декора.\n\nПрограмма ставит своей целью познакомить учащихся с наследием художественной обработки дерева, привить любовь к традиционному художественному ремеслу, обучить практическим навыкам резьбы по дереву и умению создавать собственные композиции.",
                'image_path' => 'image/rd-3.jpg',
            ],
        ])->map(fn (array $item) => CreativityType::query()->updateOrCreate(
            ['slug' => $item['slug']],
            $item
        ))->keyBy(fn (CreativityType $type) => $type->slug);

        $classes = [
            [
                'creativity_type_id' => $types['architectural-modeling']->id,
                'instructor_id' => $instructor->id,
                'title' => 'Моделирование моделей транспорта',
                'description' => 'Мастер-класс «Моделирование моделей транспорта» научит основам моделирования различных видов транспортных средств. Ученики строят, испытывают и запускают модели судов, самолетов и автомобилей.',
                'class_date' => Carbon::today()->addDays(2)->format('Y-m-d'),
                'start_time' => '09:00',
                'end_time' => '11:00',
                'max_participants' => 8,
                'price' => 2400,
            ],
            [
                'creativity_type_id' => $types['architectural-modeling']->id,
                'instructor_id' => $instructor->id,
                'title' => 'Моделирование зданий и сооружений',
                'description' => 'Опытные педагоги научат моделировать различные элементы малоэтажных жилых и нежилых зданий, конструировать разные виды крыш и стен, а также собирать из элементов здания различной архитектуры.',
                'class_date' => Carbon::today()->addDays(3)->format('Y-m-d'),
                'start_time' => '13:00',
                'end_time' => '15:00',
                'max_participants' => 10,
                'price' => 3200,
            ],
            [
                'creativity_type_id' => $types['kulinariya']->id,
                'instructor_id' => $instructor->id,
                'title' => 'Шоколадные поделки',
                'description' => 'Шоколадные фонтаны, фруктовые пальмы, приготовление шоколадных конфет, мороженого и других сладостей. Мы научим вас делать любой праздник оригинальнее и вкуснее.',
                'class_date' => Carbon::today()->addDays(4)->format('Y-m-d'),
                'start_time' => '11:00',
                'end_time' => '13:00',
                'max_participants' => 12,
                'price' => 2100,
            ],
            [
                'creativity_type_id' => $types['kulinariya']->id,
                'instructor_id' => $instructor->id,
                'title' => 'Приготовление стейков',
                'description' => 'Мы все любим стейки, но не у каждого из нас получается их правильно приготовить. На этом мастер-классе мы расскажем вам всё о стейках: как выбрать мясо, какую часть использовать для того или иного вида стейка, какие степени прожарки бывают. Мы приготовим гарнир и идеальный соус.',
                'class_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
                'start_time' => '15:00',
                'end_time' => '17:00',
                'max_participants' => 8,
                'price' => 2500,
            ],
            [
                'creativity_type_id' => $types['wood-carving']->id,
                'instructor_id' => $secondInstructor->id,
                'title' => 'Геометрическая резьба по дереву',
                'description' => 'Данный мастер-класс для начинающих, знакомит с геометрической резьбой, с самых основных элементов. Несложными движениями и творческим комбинированием создаются удивительные узоры на дереве.',
                'class_date' => Carbon::today()->addDays(6)->format('Y-m-d'),
                'start_time' => '09:00',
                'end_time' => '11:00',
                'max_participants' => 6,
                'price' => 2800,
            ],
            [
                'creativity_type_id' => $types['wood-carving']->id,
                'instructor_id' => $secondInstructor->id,
                'title' => 'Деревянные игрушки',
                'description' => 'На мастер-классе вы научитесь вырезать фигурки животных из качественных пород дерева с помощью профессиональных инструментов. Обработка фигурок натуральными составами обеспечит прочность, долговечность и экологичность созданных игрушек.',
                'class_date' => Carbon::today()->addDays(7)->format('Y-m-d'),
                'start_time' => '11:00',
                'end_time' => '13:00',
                'max_participants' => 6,
                'price' => 3000,
            ],
        ];

        foreach ($classes as $classData) {
            $masterClass = MasterClass::query()->updateOrCreate(
                [
                    'instructor_id' => $classData['instructor_id'],
                    'class_date' => $classData['class_date'],
                    'start_time' => $classData['start_time'],
                ],
                $classData
            );

            if ($masterClass->title === 'Приготовление стейков') {
                Enrollment::query()->firstOrCreate([
                    'master_class_id' => $masterClass->id,
                    'user_id' => $visitor->id,
                ]);
            }
        }
    }
}
