<?php
    //Response from Telegram
    $data = json_decode(file_get_contents('php://input'), TRUE);
    
    //Saving chat into text file
    file_put_contents('file.txt', '$data: '. print_r($data, 1). "\n", FILE_APPEND);

    if($data['callback_query']){
        $data = $data['callback_query'];
    }
    elseif($data['channel_post']){
        $data = $data['channel_post'];
    }
    else{
        $data = $data['message'];
    }    

    // API TOKEN    
    $token = 'YOUR_TOKEN';
   
   //User ID
    $user_id = $data['chat']['id'];

    //Message to user
    $message_in = $data['text'];
    

    if($message_in){       
                 
        // $message_out = " Assalamu alaykum!
        // \n\nSiz ushbu botga Ingliz tilida so'z kiritib, Ingliz tilidagi so'zlarning sinonimlari va qisqacha ta'rifini olishingiz mumkin! Ushbu bot, Inglizcha so'zlarning sinonim va ta'rifni <a href='https://www.merriam-webster.com/'>merriam-webster.com</a> saytidan oladi. Botni ishga tushirish uchun shunchaki Ingliz tilidagi so'zni yozing.
        // \n\nAgarda bot ishlamay qolsa /start buyrug'ini bering va qaytadan ishlatib ko'ring!";  

        $message_in = str_replace('/', "", $message_in);

        $input = $message_in;

        // This function grabs the word from Merriam Webster API in JSON format.
        $word = json_decode(file_get_contents("https://www.dictionaryapi.com/api/v3/references/thesaurus/json/".$input."?key=YOUR_KEY"), TRUE);
   
        $synonyms = "<strong>Synonyms | Thesaurus:</strong> \n\n". implode(", ", $word[0]['meta']['syns'][0]) ?  "<strong>Synonyms | Thesaurus:</strong> \n\n". implode(", ", $word[0]['meta']['syns'][0]) : "";
   
        $shortDef = "<strong>Short definition:</strong> " . implode(", ",   $word[0]['shortdef']) ? "<strong>Short definition:</strong> " . implode(", ",   $word[0]['shortdef']) : "";
   
        $phraseList= "<strong>Phrase example:</strong> " . implode(", ",  $word[0]['def'][0]['sseq'][0][0][1]['phrase_list'][0][0]).
   
        ", ". implode(", ",  $word[0]['def'][0]['sseq'][0][0][1]['phrase_list'][0][1]). ", ". implode(", ",  $word[0]['def'][0]['sseq'][0][0][1]['phrase_list'][0][2])
   
        ?  "<strong>Phrase example:</strong> " . implode(", ",  $word[0]['def'][0]['sseq'][0][0][1]['phrase_list'][0][0]).", ".implode(", ",  $word[0]['def'][0]['sseq'][0][0][1]['phrase_list'][0][1]).", ".implode(", ",  $word[0]['def'][0]['sseq'][0][0][1]['phrase_list'][0][2])
   
         : "";
       //End of converting Array into String using implode() method        
       
        
       $messageWord = "<strong>Kiritilgan so'z:</strong>\t\t\t\t\t\t\t "."<i>".strtoupper($input)."</i>"."\n\n" .$synonyms."\n\n\n".$shortDef."\n\n\n". $phraseList."\n\n"."Manba: <a href='https://www.merriam-webster.com/'>merriam-webster.com</a>";

       $message_out = $messageWord;

       sendMessage($token, $user_id, $message_out);
    }
    
    else{
        $message_out = "Siz qidirgan so'z topilmadi. Iltimos so'zni to'g'ri kiriting.\n\n<i>Hint: So'zni biror ortiqcha belgilarsiz oddiy ko'rinishida yozing. So'zni yozilishi(<b>spelling</b>)ga ham e\'tibor bering</i>";
    }   
   #======================================== Function to send message to Telegram =================================================
   function sendMessage($token, $user_id, $message_out)
   {
       $params = [
           'chat_id' => $user_id,
           'text'    => $message_out,
           'parse_mode' => 'html'
       ];
       
       file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?'.http_build_query($params));
   }    
?>
