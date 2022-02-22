
@extends('layouts.app')

@section('content')
<h1>Test</h1>
<input type="text" id="text">
<button id="submit" onclick="sentMessage()">送出</button>
<script type="application/javascript">
  window.onload = function(){
    Echo.join('chat')
      .here((user) => {
        console.log(user);
      })
      .listen('SentMessage', (data) => {
          console.log(data);
      })
      .error((error) => {
          console.error(error);
      });
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

</script>
@endsection

