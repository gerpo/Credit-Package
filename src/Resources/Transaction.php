<?php


namespace Gerpo\DmsCredits\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{
    public function toArray($request)
    {
        $eventName = explode('\\', $this->event_class);
        return $this->assignEventData(array_pop($eventName));
    }

    private function assignEventData($eventName)
    {
        switch($eventName)
        {
            case 'AccountCreated': return [
                'event_name' => $eventName,
                'message' => $this->event_properties['message'] ?? '',
                'owner_id' => $this->event_properties['accountAttributes']['owner_id'],
                'owner_type' => $this->event_properties['accountAttributes']['owner_type'],
                'created_at' => $this->created_at,
            ];
            case 'AccountEnabled': return [];
            case 'AccountDisabled': return [];
            case 'CreditsAdded': return [
                'event_name' => $eventName,
                'message' => $this->event_properties['message'],
                'amount' => $this->event_properties['amount'],
                'created_at' => $this->created_at,
            ];
            case 'CreditsSubtracted': return [
                'event_name' => $eventName,
                'message' => $this->event_properties['message'],
                'amount' => -$this->event_properties['amount'],
                'created_at' => $this->created_at,
            ];
            default: return [];
        }
    }
}