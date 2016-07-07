<?php

namespace pimax\Messages;


/**
 * Class Message
 *
 * @package pimax\Messages
 */
class Message
{
    /**
     * @var integer|null
     */
    protected $recipient = null;

    /**
     * @var string
     */
    protected $text = null;

    /**
     * @var null|array
     */
    protected $quick_replies = null ; 

    /**
     *  Message constructor.
     * @param $recipient
     * @param $text 
     * @param $quick_replies
     */
    public function __construct($recipient, $text,$quick_replies=null)
    {
        $this->recipient = $recipient ; 
        $this->text = $text ; 
        $this->quick_replies = $quick_replies ; 
    }

    /**
     * Get message data
     *
     * @return array
     */
    public function getData()
    {
        $result =  [
            'recipient' =>  [
                'id' => $this->recipient
            ],
            'message' => [
                'text' => $this->text
            ]
        ];
        
        if (! is_null ( $this->quick_replies ))
        {
            $temp = [] ; 
            $result['message']['quick_replies']=[] ; 
            
            foreach ( $this->quick_replies as $reply )
            {
                $temp[]=$reply->getData() ;
            }
            $result['message']['quick_replies']=$temp;
        }
        
        return $result ;
    }
}