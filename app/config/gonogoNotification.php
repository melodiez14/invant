<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class gonogoNotification extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'idms:notify_gonogo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify gonogo evaluation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $proposals_cek = Proposal::where('final_status','w')->get();

        foreach($proposals_cek as $proposal){
            $proposal_due_date = $proposal->opportunity->proposal_due_date;
            $date_now = new DateTime('now');
            if($proposal->final_status == 'w' && date_format($date_now, 'Y-m-d') == date('Y-m-d', strtotime('-1 day', strtotime($proposal_due_date))))
            {
                foreach ($proposal->evaluators as $evaluator)
                {
                    $this->evaluatorNotification($evaluator, $proposal);
                }
            }

            if ($proposal->final_status == 'w' && $date_now == $proposal_due_date)
            {
                $this->finalizeProposal($proposal);
            }
        }

    }

    public function finalizeProposal($proposal)
    {
        $proposal->load(['evaluators' => function($query) {
            return $query->where('status', true)->where('other_score', '!==', 0);
        }, 'answers' => function ($query) {
            return $query->with('question');
        }]);

        $score = [
            'avg' => [],
            'point' => []
        ];

        // populate categories value
        $categories = [];

        foreach($proposal->answers as $val) {
            $categories[$val->question->question_category_id][] = $val;
        }

        foreach ($categories as $category_id => $questions) {
            $score['avg'][$category_id] = 0;
            foreach ($questions as $question) {
                $score['avg'][$category_id] += $question->score;
            }
            $score['avg'][$category_id] /= count($questions);
        }

        // populate other score value
        $score['avg']['other_score'] = 0;
        $decission = 0;;
        foreach ($proposal->evaluators as $evaluator) {
            $score['avg']['other_score'] += $evaluator->pivot->other_score;
            $decission += $evaluator->pivot->decission ? 100 : 0;
        }
        $score['avg']['other_score'] /= count($proposal->evaluators);

        // make final scoring array
        $point = 0;
        foreach ($score['avg'] as $key => $value) {
            if ($value >= 6.5) {
                $score['point'][$key] = 100;
            } elseif ($value >= 3.6) {
                $score['point'][$key] = 50;
            } else {
                $score['point'][$key] = 0;
            }
            $point += $score['point'][$key];
        }
        $point /= count($score['avg']);

        $final_score = ($point + $decission) / (count($score['point']) + count($proposal->evaluators));

        DB::beginTransaction();
        try {
            if ($final_score >= 50) {
                $proposal->fill(['final_status' => "G"]);
                $proposal->fill(['completed_at' => new DateTime('now')]);
            } else {
                $proposal->fill(['final_status' => "N"]);
                $proposal->fill(['completed_at' => new DateTime('now')]);
            }
            $proposal->save();
            DB::commit();
            return Redirect::back()->with(['success-message' => "Proposal has been finalized!"]);
        } catch (Exception $e) {
            return Redirect::back()->with(['error-message' => "Something errors, please try again in a few moments"]);
            DB::rollback();
        }
    }

    public function evaluatorNotification($evaluator, $proposal)
    {
        $notification = $evaluator->newNotification()
                ->withUrl(route('proposals.show', $proposal->id))
                ->withRemark((Auth::user()->profile ? Auth::user()->profile->name : 'Admin' ). " has Assigned you to ".$proposal->project_idea." assessment");
        $proposal->notifications()->save($notification);
    }

    public function sendEmail(){

        $pesan = Input::get('pesan');
        $idea = Input::get('idea');
        $id = Input::get('id');

        Mail::send('proposals.email', array('pesan'=> $pesan,'idea' =>$idea, 'id'=>$id), function($message)
        {
            $email = Input::get('email');
            $message->to($email, 'John Smith')->subject('Welcome!');
        });

        $this->info("New $counter evaluation notification has been sent");
    }

    private function sendNotification($evaluator, $proposal){
        $counter = 0;

        $this->info("New $counter evaluation notification has been sent");
    }
}
