<?php
return [
    'required' => ':attribute field is required.',
    'youtube_link.regex' => 'The link must be a valid link.',
];
//SELECT FIELD_ID,f.label AS field_label,fs.field_response FROM form_submissions fs JOIN form_fields f ON fs.field_id = f.id WHERE fs.steps=3 AND FIND_IN_SET('1',f.scheme_project_type);