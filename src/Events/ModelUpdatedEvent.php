<?php
namespace DxsRavel\Essentials\Events;

//use App\Podcast;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class ModelUpdatedEvent extends Event{
    use SerializesModels;
    public $Model;
    public $OldModel;
    public $NewModel;

    /**
     * Create a new event instance.          
     * @return void
     */
    public function __construct($OldModel, $Model, $NewModel)
    {
        $this->OldModel = $OldModel;
        $this->Model = $Model;
        $this->NewModel = $NewModel;
    }
}