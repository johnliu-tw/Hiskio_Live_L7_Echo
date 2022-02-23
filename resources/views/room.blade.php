
@extends('layouts.app')

@section('content')
<h3>Current User</h3>
<div id="users"></div>
<h3>Current Message</h3>
<div id="chats">
    @foreach ($data as $message)
        <p>{{ $message->created_at }} {{ $message->user->name }} {{ $message->message }}</p>
    @endforeach
</div>
<input type="text" id="text" onkeyup="sentTyping()">
<button id="submit" onclick="sentMessage()">送出</button>
<script type="application/javascript">
  let userEmail = "{{ auth()->user()->email }}"
  window.onload = function(){
    Echo.join('chat')
      .here((users) => {
        users.forEach(function(user){
            createUserElement(user)
        })
      })
      .joining((user) => {
        createUserElement(user)
      })
      .leaving((user) => {
          console.log(user);
      })
      .listen('SentMessage', (data) => {
        let newElement = document.createElement('p');
        chatElement = document.getElementById('chats')
        newElement.textContent = `${data.message.created_at} ${data.user.name}: ${data.message.message}`
        chatElement.append(newElement)
      })
      .listenForWhisper('typing', (e) => {
        console.log(e.email);
      })
      .error((error) => {
          console.error(error);
      });
  }

  function createUserElement(user){
    let newElement = document.createElement('p');
    userElement = document.getElementById('users')
    newElement.setAttribute("id", user.email);
    newElement.textContent = user.name
    if(document.getElementById(user.email) === null){
        userElement.append(newElement)
    }
  }

  function sentMessage(){
    let message = document.getElementById('text').value
    fetch('/send-message', {
        method: 'POST',
        body: JSON.stringify({data: message}),
        headers: {
            'content-type': 'application/json'
        },
    })
  }

  function sentTyping(){
    Echo.join('chat')
        .whisper('typing', {
            email: userEmail
        });
  }

</script>
@endsection

