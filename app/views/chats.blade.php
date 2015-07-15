@extends ('layouts.master')

@section ('content')

	<div class="container">
		<div class="row">
			
			<div class="col-md-offset-4 col-md-4">
				
				<!-- <h1 id="greeting">Hello, <span id="username">{{ $loggedUser->username }}</span></h1>

				<div id="chat-window" class="col-md-12">
					
				</div>
				
				<div class="col-md-12">
		
					<div id="typingStatus" class="col-md-12" style="padding: 15px"></div>

					{{ Form::text('chat-message', null, [
						'class'       =>'form-control col-md-12', 
						'id'          =>'text', 
						'autofocus'   =>'', 
						'onblur'			=>'notTyping()',
						'placeholder' =>'Type your message']) }}
				</div> -->

<div class="messenger bg-white">
    <div class="chat-header text-white bg-gray-dark">
        Real-time Chat
        <a href="#" id="chat-toggle" class="pull-right chat-toggle">
            <span class="glyphicon glyphicon-chevron-down"></span>
        </a>
    </div>
    <div class="messenger-body open">
        <ul class="chat-messages" id="chat-log">
 
        </ul>
        <div class="chat-footer">
            <div class="p-lr-10">
                <input type="text" id="chat-message"
                    class="input-light input-large brad chat-search" placeholder="Your message...">
            </div>
        </div>
    </div>
</div>
 


			</div>

		</div>
	</div>

@stop

@section ('script')
<script>
    $(function(){
 
        // var fake_user_id = Math.floor((Math.random()*1000)+1);
        var user_id = {{ Auth::user()->id }};
        //make sure to update the port number if your ws server is running on a different one.
        window.app = {};
 
        app.BrainSocket = new BrainSocket(
                // new WebSocket('ws://192.168.1.104:8080'),
                new WebSocket('ws://sattapatta.com:8080'),
                new BrainSocketPubSub()
        );
 
        app.BrainSocket.Event.listen('generic.event',function(msg){
            console.log(msg);
            if(msg.client.data.user_id == user_id){
                $('#chat-log').append('<li><img src="http://sattapatta.com/{{ Auth::user()->photoURL }}" class="img-circle" width="26"><div class="message">'+msg.client.data.message+'</div></li>');
            }else{
                var str_test='<li class="pull-right"><img src="'+msg.client.data.user_portrait+'" class="img-circle" width="26"><div class="message">'+msg.client.data.message+'</div></li>';
                $('#chat-log').append(str_test);
            }
        });
 
        app.BrainSocket.Event.listen('app.success',function(data){
            console.log('An app success message was sent from the ws server!');
            console.log(data);
        });
 
        app.BrainSocket.Event.listen('app.error',function(data){
            console.log('An app error message was sent from the ws server!');
            console.log(data);
        });
 
        $('#chat-message').keypress(function(event) {
 
            if(event.keyCode == 13){
 
                app.BrainSocket.message('generic.event',
                        {
                            'message':$(this).val(),
                            'user_id':user_id,
                            'user_portrait':'http://sattapatta.com/{{ Auth::user()->photoURL}}'
                        }
                );
                $(this).val('');
 
            }
 
            return event.keyCode != 13; }
        );
    });
</script>
@stop