 <!DOCTYPE html>
   <html>
   <head>
       <title>Organization Invitation</title>
   </head>
   <body>
       <h1>You've been invited to join {{ $organizationName }}</h1>
       <p>Click the link below to register:</p>
       <a href="{{ $inviteLink }}">Register</a>
       <p>If the link above doesn't work, please copy the following link: {{ $inviteLink }}</a></p>
   </body>
</html>