<?php
namespace DxsRavel\Essentials\Events;

//use App\Podcast;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class ModelDeletedEvent extends Event{
    use SerializesModels;
    public $Model;
    public $NewModel;

    /**
     * Create a new event instance.          
     * @return void
     */
    public function __construct($Model, $NewModel)
    {
        $this->Model = $Model;
        $this->NewModel = $NewModel;
    }
}