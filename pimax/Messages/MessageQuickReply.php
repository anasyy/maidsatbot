<?php
/**
 *  @file MessageQuickReply.php
 *  @author Anas Yassine <anasyy.magna@gmail.com>
 *  @date 2016/07/07
 */
 
 namespace pimax\Messages ;
 
 /**
  *  Class MessageQuickReply
  * 
  *  @package pimax\Messages
  */
  class MessageQuickReply
  {
      
    
      /**
       *  Title
       * 
       *  @var string|null
       */
      protected $title = null ; 
      
      /**
       *  Payload
       * 
       * @var string|null
       */
      protected $payload = null ; 
      
      
      /**
       * MessageQuickReply constructor
       * 
       * @var string $title
       * @var string $payload
       */
      public function __construct($title,$payload)
      {
        $this->title=$title;
        $this->payload=$payload;
      }
      
      /**
       *  Get data
       * 
       * @return array
       */
       public function getData()
       {
        
        $result= [
         'content_type' => 'text' , 
         'title'=>$this->title ,
         'payload'=>$this->payload
        ];
        
       }

 }
?>