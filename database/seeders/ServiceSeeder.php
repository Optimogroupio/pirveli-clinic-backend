<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'ოფთალმოლოგიის დეპარტამენტი',
                'description' => 'განყოფილება აღჭურვილია Topcon-ის ბიომიკროსკოპით, ოფთალმომეტრით, უკონტაქტო ',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ზოგადი ქირურგია',
                'description' => 'ალერგოლოგიის განყოფილება აღჭურვილია MicroLab Mk8 და NObreath-ის თანამედროვე აპარატურით.US-ის ულტრათანამედროვე ციფრული ენდოსკოპიური აპარატი EVIS X1, რომელიც  გამოირჩევა  გამოსახულების მაღალი ხარისხით, რის შედეგადაც ხდება პათოლოგიური პროცესის უფრო მკაფიოდ შესწავლა.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'სისხლძარღვთა ქირურგია',
                'description' => 'ანგიოლოგიისა და სისხლძარღვთა ქირურგიის განყოფილება აღჭურვილია ულტრათანამედროვე აპარატურით - Siemens Artis Zee, Siemens Arcadis Avantic, Siemens Acuson NX3.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'პედიატრია',
                'description' => 'თოდუას კლინიკის აბესტეზიოლოგიის და რეანიმატოლოგიის დეპარტამენტი აღჭურვილის ულტრათანამედროვე',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'გინეკოლოგიის დეპარტამენტი',
                'description' => 'თოდუას კლინიკაში არსებული ულტრათანამედროვე მატერიალურ - ტექნიკური ბაზა საშუალებას გვაძლევს დროულად და კომპლექსურად ერთ სივრცეში გამოვიკვლიოთ პაციენტი და დავუნიშნოთ შესაბამისი მკურნალობა.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ნევროლოგიის დეპარტამენტი',
                'description' => 'თოდუას კლინიკის ბირთვული მედიცინის დეპარტამენტი აღჭურვილია General Electric-ის უახლესი თაობის 5-რგოლიანი პოზიტრონ ემისიური ტომოგრაფით - Discovery IQ-ი და SIEMENS-ის ფირმის 2 უახლესი აპარატით ერთ-ფოტონ ემისიული კომპიუტერული ტომოგრაფით -Symbia intevo SPECT/CT და ერთ-ფოტონ ემისიული ტომოგრაფით Symbia Evo.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'პროქტოლოგის დეპარტამენტი',
                'description' => 'განყოფილებაში ტარდება გასტროენტეროლოგიური პათოლოგიების დიაგნოსტიკისა და მკურნალობის სრული სპექტრი',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ინტერიერი',
                'description' => 'თოდუას კლინიკაში ხელმისაწვდომია დერმატოლოგიური დაავადებების დიაგნოსტირების მომსახურების სრული სპექტრი. მომსახურების სფეროები: ზოგადი დერმატოლოგია, ქირურგიული დერმატოლოგია, პედიატრიული დერმატოლოგია',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ნეიროქირურგიის დეპარტამენტი',
                'description' => 'ენდოკრინოლოგიის დეპარტამენტი მოიცავს ენდოკრინულ პათოლოგიათა სრული სპექტრის დიაგნოსტიკასა და მკურნალობას თანამედროვე რეკომენდაციებზე დაყრდნობით.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ალერგოლოგიის დეპარტამენტი',
                'description' => 'თოდუას კლინიკაში არსებული ულტრათანამედროვე მატერიალურ - ტექნიკური ბაზა საშუალებას გვაძლევს დროულად და კომპლექსურად ერთ სივრცეში გამოვიკვლიოთ პაციენტი და დავუნიშნოთ შესაბამისი მკურნალობა.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ოტორინოლარინგოლოგის დეპარტამენტი',
                'description' => 'ალერგოლოგიის განყოფილება აღჭურვილია MicroLab Mk8 და NObreath-ის თანამედროვე აპარატურით.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'კარდიოლოგიის დეპარტამენტი',
                'description' => 'ანგიოლოგიისა და სისხლძარღვთა ქირურგიის განყოფილება აღჭურვილია ულტრათანამედროვე აპარატურით - Siemens Artis Zee, Siemens Arcadis Avantic, Siemens Acuson NX3.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ფსიქოთერაპიის დეპარტამენტი',
                'description' => 'თოდუას კლინიკის აბესტეზიოლოგიის და რეანიმატოლოგიის დეპარტამენტი აღჭურვილის ულტრათანამედროვე',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ონკოლოგიის დეპარტამენტი',
                'description' => 'თოდუას კლინიკის ბირთვული მედიცინის დეპარტამენტი აღჭურვილია General Electric-ის უახლესი თაობის 5-რგოლიანი პოზიტრონ ემისიური ტომოგრაფით - Discovery IQ-ი და SIEMENS-ის ფირმის 2 უახლესი აპარატით ერთ-ფოტონ ემისიული კომპიუტერული ტომოგრაფით -Symbia intevo SPECT/CT და ერთ-ფოტონ ემისიული ტომოგრაფით Symbia Evo.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'გასტროენტეროლოგია',
                'description' => 'განყოფილებაში ტარდება გასტროენტეროლოგიური პათოლოგიების დიაგნოსტიკისა და მკურნალობის სრული სპექტრი',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'რეაბილიტაცია',
                'description' => 'თოდუას კლინიკაში არსებული ულტრათანამედროვე მატერიალურ - ტექნიკური ბაზა საშუალებას გვაძლევს დროულად და კომპლექსურად ერთ სივრცეში გამოვიკვლიოთ პაციენტი და დავუნიშნოთ შესაბამისი მკურნალობა.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ლაბორატორია',
                'description' => 'თოდუას კლინიკაში ხელმისაწვდომია დერმატოლოგიური დაავადებების დიაგნოსტირების მომსახურების სრული სპექტრი. მომსახურების სფეროები: ზოგადი დერმატოლოგია, ქირურგიული დერმატოლოგია, პედიატრიული დერმატოლოგია',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ორთოპედ-ტრავმატოლოგიის დეპარტამენტი',
                'description' => 'ენდოკრინოლოგიის დეპარტამენტი მოიცავს ენდოკრინულ პათოლოგიათა სრული სპექტრის დიაგნოსტიკასა და მკურნალობას თანამედროვე რეკომენდაციებზე დაყრდნობით.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'დერმატოლოგიის დეპარტამენტი',
                'description' => 'ენდოკრინოლოგიის დეპარტამენტი მოიცავს ენდოკრინულ პათოლოგიათა სრული სპექტრის დიაგნოსტიკასა და მკურნალობას თანამედროვე რეკომენდაციებზე დაყრდნობით.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ენდოკრინოლოგიის დეპარტამენტი',
                'description' => 'ენდოკრინოლოგიის დეპარტამენტი მოიცავს ენდოკრინულ პათოლოგიათა სრული სპექტრის დიაგნოსტიკასა და მკურნალობას თანამედროვე რეკომენდაციებზე დაყრდნობით.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'დიაგნოსტიკური კვლევები',
                'description' => 'ენდოკრინოლოგიის დეპარტამენტი მოიცავს ენდოკრინულ პათოლოგიათა სრული სპექტრის დიაგნოსტიკასა და მკურნალობას თანამედროვე რეკომენდაციებზე დაყრდნობით.',
                'service_category_id' => rand(1, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Service::insert($data);
    }
}
