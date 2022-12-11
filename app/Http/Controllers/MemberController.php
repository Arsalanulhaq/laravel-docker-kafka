<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessKafkaProducerForMembers;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = Member::all();

        return response()->json([
            'status' => true,
            'members' => $members
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $this->kafkaConsumer();
        $member = Member::create($request->all());
        // $this->kafkaProduce($member);
        dispatch(new ProcessKafkaProducerForMembers($member));

        return response()->json([
            'status' => true,
            'message' => "Member Created successfully!",
            'member' => $member
        ], 200);
    }

    public function log($msg)
    {
        Log::info($msg);
    }

    public function kafkaConsumer()
    {
//        $this->log('consumer start');
//        $kafkaManager = new \Ensi\LaravelPhpRdKafka\KafkaManager();
//        $consumer = $kafkaManager->consumer('test');
//        $consumer->subscribe(['quickstart']);
//        $this->log('consumer start....');

        $conf = new \RdKafka\Conf();
        $conf->set('log_level', (string) LOG_DEBUG);
        $conf->set('debug', 'all');
        $rk = new \RdKafka\Consumer($conf);
        $rk->addBrokers("broker:9092");

        $topic = $rk->newTopic("quickstart");

    // The first argument is the partition to consume from.
    // The second argument is the offset at which to start consumption. Valid values
    // are: RD_KAFKA_OFFSET_BEGINNING, RD_KAFKA_OFFSET_END, RD_KAFKA_OFFSET_STORED.
//        $topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);
        $topic->consumeStart(0, -2);

        while (true) {
            $message = $topic->consume(0, 10*1000);
            if($message){
                $this->log('1-');
                if ($message->err) {
                    $this->log($message->errstr());
                    echo $message->errstr();
                    break;
                } else {
                    $this->log($message->payload);
                    echo $message->payload . '<br>';
                }
            }
            else{
                $this->log('nothing to consume');
                echo 'Nothing to consume';
                break;
            }
//            switch ($message->err) {
//                case RD_KAFKA_RESP_ERR_NO_ERROR:
//                    $this->info($message->payload);
//                    $this->processMessage($message); // do something with the message
//                    // $consumer->commitAsync($message); // commit offsets asynchronously if you set 'enable.auto.commit' => false, in config/kafka.php
//                    break;
//                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
//                    echo "No more messages; will wait for more\n";
//                    break;
//                case RD_KAFKA_RESP_ERR__TIMED_OUT:
//                    // this also happens when there is no new messages in the topic after the specified timeout: https://github.com/arnaud-lb/php-rdkafka/issues/343
//                    echo "Timed out\n";
//                    break;
//                default:
//                    throw new Exception($message->errstr(), $message->err);
//                    break;
//            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
//    public function show(Member $member)
//    {
//        return view('members.show',compact('member'));
//    }
    public function show($id)
    {
        $member = Member::find($id);
        if(!empty($member))
        {
            return response()->json($member);
        }
        else
        {
            return response()->json([
                "message" => "Member not found"
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        return view('members.edit',compact('member'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
//        dd($member);
        $member->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "Member Updated successfully!",
            'member' => $member
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return response()->json([
            'status' => true,
            'message' => "Member Deleted successfully!",
        ], 200);
    }
}
