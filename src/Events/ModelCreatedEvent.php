<?php
namespace DxsRavel\Essentials\Events;

//use App\Podcast;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class ModelCreatedEvent extends Event{
    use SerializesModels;
    public $Model;

    /**
     * Create a new event instance.          
     * @return void
     */
    public function __construct($Model)
    {
        $this->Model = $Model;
    }
    
    public function broadcastAs()
    {
        return 'dxsravel.model.created';
    }
}