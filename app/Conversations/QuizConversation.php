<?php

namespace App\Conversations;

use App\Models\Track;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;

class QuizConversation extends Conversation
{
 /** @var Track */
 protected $quizTracks;

 /** @var Question */
 protected $quizQuestions;

 /** @var integer */
 protected $userPoints = 0;

 /** @var integer */
 protected $userCorrectAnswers = 0;

 /** @var integer */
 protected $questionCount;

 /** @var integer */
 protected $currentQuestion = 1;

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->quizTracks = Track::all();
        $this->selectTrack();
    }

    private function selectTrack()
    {
        $this->say(
            "We have " . $this->quizTracks->count() . " tracks. \n You have to choose one to continue.",
            ['parse_mode' => 'Markdown']
        );
        $this->bot->typesAndWaits(1);

        return $this->ask($this->chooseTrack(), function (BotManAnswer $answer) {
            $selectedTrack = Track::find($answer->getValue());

            if (!$selectedTrack) {
                $this->say('Sorry, I did not get that. Please use the buttons.');
                return $this->selectTrack();
            }

            return $this->setTrackQuestions($selectedTrack);
        }, [
            'parse_mode' => 'Markdown'
        ]);
    }


}
