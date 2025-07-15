<?php
return [
    '14.regex' => 'The mobile number must start with 6, 7, 8, or 9 and must be 10 digits long.',
];

$responses = $field->formSubmission->map(fn($submission) => $submission->field_response);
                $field->form_submission = $responses->all();
               // $field->response = $responses->first() ?? null;
                unset($field->formSubmission);
                return $field;
//SELECT f.label AS field_label,fs.field_response FROM form_submissions fs JOIN form_fields f ON fs.field_id = f.id WHERE fs.steps=3 AND FIND_IN_SET('1',f.scheme_project_type);