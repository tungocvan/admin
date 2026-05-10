<?php

namespace Modules\Chat\Services;

use Modules\Chat\Models\InternalMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InternalChatService
{
    public function getMessages(int $userId)
    {
        $authId = Auth::guard('admin')->id();

        return InternalMessage::query()
            ->where(function ($q) use ($authId, $userId) {
                $q->where('from_id', $authId)
                    ->where('to_id', $userId);
            })
            ->orWhere(function ($q) use ($authId, $userId) {
                $q->where('from_id', $userId)
                    ->where('to_id', $authId);
            })
            ->orderBy('id')
            ->get();
    }

    public function sendMessage(int $toUserId, string $message)
    {
        $authId = Auth::guard('admin')->id();

        $chat = InternalMessage::create([
            'from_id' => $authId,
            'to_id' => $toUserId,
            'message' => $message,
        ]);

        $payload = [
            'id' => $chat->id,
            'from_id' => $chat->from_id,
            'to_id' => $chat->to_id,
            'message' => $chat->message,
            'created_at' => $chat->created_at->toISOString(),
        ];

        $this->broadcast([
            'event' => 'InternalMessageSent',
            'channel' => $this->makeRoom($authId, $toUserId),
            'data' => $payload,
        ]);

        return $chat;
    }

    public function makeRoom($a, $b): string
    {
        $ids = [$a, $b];

        sort($ids);

        return 'dm-' . $ids[0] . '-' . $ids[1];
    }

    protected function broadcast(array $payload): void
    {
        try {

            $url =
                config('services.nodejs.url', env('NODEJS_SERVER_URL'))
                . '/broadcast';

            logger('========== INTERNAL CHAT ==========');

            logger('NODE URL', [
                'url' => $url,
            ]);

            logger('NODE PAYLOAD', $payload);

            $response = Http::withHeaders([
                'X-Bridge-Secret' => env('BRIDGE_SECRET_KEY'),
                'Content-Type' => 'application/json',
            ])
                ->timeout(5)
                ->post($url, $payload);

            logger('NODE RESPONSE', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {

            logger('NODE ERROR', [
                'message' => $e->getMessage(),
            ]);
        }
    }
    protected function broadcastToNodeJS(array $payload): void
    {
        try {

            $url =
                env(
                    'NODEJS_SERVER_URL',
                    'http://127.0.0.1:6001'
                ) . '/broadcast';

            logger('========== NODE DEBUG ==========');

            logger('NODE URL', [
                'url' => $url
            ]);

            logger('NODE PAYLOAD', $payload);

            $response = Http::withHeaders([
                'X-Bridge-Secret' => env('BRIDGE_SECRET_KEY'),
                'Content-Type' => 'application/json',
            ])
                ->timeout(5)
                ->post($url, $payload);

            logger('NODE RESPONSE', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {

            logger('NODE ERROR', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
