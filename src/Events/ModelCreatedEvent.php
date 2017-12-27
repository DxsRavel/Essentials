<?php
namespace DxsRavel\Essentials\Events;

//use App\Podcast;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class ModelCreatedEvent extends Event{
    use SerializesModels;
    public $Model;
    public $NewModel;

    /**
     * Create a new event instance.          
     * @return void
     */
    public function __construct($Model,$NewModel)
    {
        $this->Model = $Model;
        $this->NewModel = $NewModel;
    }
    
    public function broadcastAs()
    {
        return 'dxsravel.model.created';
    }
}