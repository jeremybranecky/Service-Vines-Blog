Vous pouvez utiliser trois m&eacute;thodes distinctes pour renseigner la ligne Exp&eacute;diteur:
<ul>
<li>Un mot: ceci sera reformat&eacute; comme ceci:  &lt;le mot&gt;@<?php echo $domain?>
<br>Par exemple: <b>information</b> deviendra <b>information@<?php echo $domain?></b>
<br>Dans la plupart des programmes email, ceci sera affich&eacute; comme provenant de <b>information@<?php echo $domain?></b>
<li>Deux mots ou plus: ceci sera reformat&eacute; comme ceci: <i>les mots que vous tapez</i> &lt;listmaster@<?php echo $domain?>&gt; (si listmaster est l&rsquo;adresse par d&eacute;faut dans votre configuration)
<br>Par exemple: <b>information de la liste</b> deviendra <b>information de la liste &lt;listmaster@<?php echo $domain?>&gt; </b>
<br>Dans la plupart des programmes email, ceci sera affich&eacute; comme provenant de <b> information de la liste</b>
<li>Rien, ou plusieurs mots et une adresse email: ceci sera reformat&eacute; comme ceci: <i>Mots</i> &lt;addresseemail&gt;
<br>Par exemple: <b>Mon Nom mon@email.com</b> deviendra <b>Mon Nom &lt;mon@email.com&gt;</b>
<br>Dans la plupart des programmes email, ceci sera affich&eacute; comme provenant de <b>Mon Nom</b>
