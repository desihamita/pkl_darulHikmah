<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Subject;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QnaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row) {
        if ($row['question'] != 'question') {
            info('Processing question: ' . $row['question']);

            $subject = Subject::where('subject', $row['subject'])->first();

            if ($subject != null) {
                $questionId = Question::insertGetId([
                    'question' => $row['question'],
                    'subject_id' => $subject->id,
                    'explanation' => $row['explanation'],
                ]);

                for ($i = 1; $i <= 6; $i++) {
                    $optionKey = 'option_' . $i;

                    if (isset($row[$optionKey]) && $row[$optionKey] !== null) {
                        $is_correct = 0;

                        if ($row['is_correct'] == $row[$optionKey]) {
                            $is_correct = 1;
                        }

                        Answer::insert([
                            'question_id' => $questionId,
                            'answer' => $row[$optionKey],
                            'is_correct' => $is_correct,
                        ]);
                    }
                }
            } else {
                info('Mata pelajaran tidak ditemukan untuk: ' . $row['subject']);
            }
        }
    }
}