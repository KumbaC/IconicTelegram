<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Track;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Track::truncate();
        $this->addTracks();

        $tracks = Track::all();
        Question::truncate();
        Answer::truncate();

        $questionAndAnswers = $this->getData();
        foreach ($tracks as $track) {
            $questionAndAnswers->where('track', $track->id)->each(function ($question) {
                $createdQuestion = Question::create([
                    'text' => $question['question'],
                    'track_id' => $question['track'],
                    'points' => $question['points'],
                ]);

                collect($question['answers'])->each(function ($answer) use ($createdQuestion) {
                    Answer::create([
                        'question_id' => $createdQuestion->id,
                        'text' => $answer['text'],
                        'correct_one' => $answer['correct_one'],
                    ]);
                });
            });
        }
    }

    private function addTracks()
    {
        $track = new Track();
        $track->name = 'Laravel';
        $track->save();
        $track = new Track();
        $track->name = 'Django';
        $track->save();
        $track = new Track();
        $track->name = 'React';
        $track->save();
        $track = new Track();
        $track->name = 'CSS';
        $track->save();
    }


    private function getData()
    {
        return collect([
            [
                'question' => 'Is Laravel 6 an LTS release?',
                'points' => 10,
                'track' => 1,
                'answers' => [
                    ['text' => 'Yes', 'correct_one' => true],
                    ['text' => 'No', 'correct_one' => false],
                ],
            ],
            [
                'question' => 'Which of the following is a Laravel product?',
                'points' => 10,
                'track' => 1,
                'answers' => [
                    ['text' => 'Laravel Fume', 'correct_one' => false],
                    ['text' => 'Laravel Paper', 'correct_one' => false],
                    ['text' => 'Laravel Vapor', 'correct_one' => true],
                ],
            ],
            [
                'question' => 'What is the "Context API" in React?',
                'points' => 20,
                'track' => 3,
                'answers' => [
                    ['text' => 'A way to pass data through the component tree without having to pass props down manually at every level', 'correct_one' => True,],
                    ['text' => 'A tool for debugging React applications', 'correct_one' => False,],
                    ['text' => 'A way to manage the state of a component based on user input', 'correct_one' => False,],
                ]
            ],
        ]);
    }
}
