<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Member;
use Illuminate\Support\Facades\Log;

class ProcessKafkaProducerForMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $member;

    /**
     * Create a new job instance.
     *
     * @param Member $member
     * @return void
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->kafkaProduce();
    }

    public function kafkaProduce()
    {
        Log::info('Whatever you want to log: ');
        Log::info($this->member['membership_id']);
        // $producer = \Kafka::producer('producer-name'); // returns a configured RdKafka\Producer singleton.
        $producer = \Kafka::producer();
        // dd($producer);
        // or $producer = \Kafka::producer(); if you want to get the default producer.
        // or $producer = $kafkaManager->producer(); where $kafkaManager is an instance of Ensi\LaravelPhpRdKafka\KafkaManager resolved from the service container.

        // now you can implement any producer logic e.g:

        $headers = [];
        $topicName = 'quickstart';
        $topic = $producer->newTopic($topicName);
        $payload = json_encode([
            // 'body' => "Message $i in topic [$topicName]",
            'body' => "Member created {$this->member['membership_id']} in topic [$topicName]",
            'membership_id' => $this->member['membership_id'],
            'member_name' => $this->member['name'],
            'headers' => $headers
        ]);
//        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $payload);
        $topic->produce(-1, 0, $payload);
        $producer->poll(0);

        for ($flushRetries = 0; $flushRetries < 10; $flushRetries++) {
        $result = $producer->flush(10000);
//        if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
        if (0 === $result) {
            break;
        }
        }
        if (0 !== $result) {
        // Log and/or throw "Unable to flush Kafka producer, messages of topic [$topicName] might be lost.' exception.
        }

        // If you use php-fpm and producing is slow you can move its execution to the place after response has been sent.
        // This can be achieved e.g. by wrapping the whole producing or at least flushing in it in a "terminating" callback.
        // app()->terminating(function () { ... });
        Log::info('Log--end');
    }
}
