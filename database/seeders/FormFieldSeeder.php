<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scheme;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormFieldOption;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'scheme' => [
                'name' => 'Shrestha',
                'active' => 1
            ],
            'form' => [
                'name' => 'Inspection',
                'slug' => 'inspection',
                'fields' => [
                    [
                        'header'=>null,
                        'label' => 'Enter Comments',
                        'type' => 'textarea',
                        'name' => 'inspection_team_comment',
                        'placeholder' => 'Comments of the inspection Team on the overall functioning of the School/ Hostel',
                        'validation_rule' => ['required','string','max:255'],
                        'front_validation_rule'=>['required','string','max:255'],
                        'steps' => 4,
                        'order' => 37,
                        'option_id'=>null
                    ],
                      
                ]
            ]
        ];
        try {
            DB::beginTransaction();

            // $scheme = Scheme::create($data['scheme']);

            // $form = $scheme->forms()->create([
            //     'name' => $data['form']['name'],
            //     'slug' => $data['form']['slug'],
            //     'active' => 1,
            // ]);

            foreach ($data['form']['fields'] as $fieldData) {
                $optionId = null;
                if (!empty($fieldData['option'])) {
                    $option = FormFieldOption::create([
                        'name' => $fieldData['option']['name'],
                        'values' => $fieldData['option']['values'],
                        'active' => 1,
                    ]);
                    $optionId = $option->id;
                }

                FormField::create([
                    'scheme_id' => 5,
                    'form_id' => 5,
                    'header' => $fieldData['header'] ?? null,
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'name' => $fieldData['name'],
                    'placeholder' => $fieldData['placeholder'],
                    'validation_rule' => $fieldData['validation_rule'],
                    'front_validation_rule' => $fieldData['front_validation_rule'],
                    'option_id' => $fieldData['option_id'],
                    'steps' => $fieldData['steps'],
                    'order' => $fieldData['order'],
                    'active' => 1
                ]);
            }

            DB::commit();
            $this->command->info('Form seeded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error while seeding form: ' . $e->getMessage());
        }
    }
}
