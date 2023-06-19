<?php

namespace App\Console\Commands;

use App\Models\Chatbot;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FillInitialPromptCommand extends Command
{
    protected $signature = 'prompt:fill';

    protected $description = 'Fill initial prompt for all chatbots';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $initialPrompt = "You are a helpful AI customer support agent. Use the following pieces of context to answer the question at the end.
If you don't know the answer, just say you don't know. DO NOT try to make up an answer.
If the question is not related to the context, politely respond that you are tuned to only answer questions that are related to the context.

{context}

Question: {question}
Helpful answer in markdown:";

        $chatbots = Chatbot::where('prompt_message', null)->get();

        foreach ($chatbots as $chatbot) {
            $chatbot->setPromptMessage($initialPrompt);
            $chatbot->save();

            $this->info("Chatbot: " . $chatbot->getId() . " initial prompt got updated updated");
        }
    }
}
