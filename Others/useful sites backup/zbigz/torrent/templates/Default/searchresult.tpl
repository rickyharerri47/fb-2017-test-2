[searchposts]
[fullresult]
<div class="base shortstory">
	<div class="dpad">
		<h3 class="btl">[full-link]{title}[/full-link]</h3>
		<div class="bhinfo">
		[not-group=5]
			<ul class="isicons reset">
				<li>[edit]<img src="{THEME}/dleimages/editstore.png" title="Modifier" alt="Modifier" />[/edit]</li>
				<li>{favorites}</li>
			</ul>
		[/not-group]
			<span class="baseinfo radial">
				Auteur: {author} Le [day-news]{date}[/day-news]
			</span>
			[rating]<div class="ratebox"><div class="rate">{rating}</div></div>[/rating]
		</div>
		<div class="maincont">
			{short-story}
			<div class="clr"></div>
			[edit-date]<p class="editdate"><br /><i>La nouvelle a été édités: <b>{editor}</b> - {edit-date}
			<br />[edit-reason]Raison: {edit-reason}[/edit-reason]</i></p>[/edit-date]
		</div>
	</div>
	<div class="mlink">
		<span class="argmore">[full-link]<b>En savoir plus</b>[/full-link]</span>
		<span class="argviews"><span title="Vues: {views}"><b>{views}</b></span></span>
		<span class="argcoms">[com-link]<span title="Commentaire: {comments-num}"><b>{comments-num}</b></span>[/com-link]</span>
		<div class="mlarrow">&nbsp;</div>
		<p class="lcol argcat">Categorie: {link-category}</p>
	</div>
</div>
[/fullresult]
[shortresult]
<div class="dpad searchitem">
	<h3>[full-link]{title}[/full-link]</h3>
	<b>[day-news]{date}[/day-news]</b> | {link-category} | Auteur: {author}
</div>
[/shortresult]
[/searchposts]
[searchcomments]
[fullresult]
<div class="bcomment">
	<div class="dtop">
		<div class="lcol"><span><img src="{foto}" alt=""/></span></div>
		<div class="rcol">
			<span class="reply">[fast]<b>Citer</b>[/fast]</span>
			<ul class="reset">
				<li><h4>{author}</h4></li>
				<li>{date}</li>
			</ul>
			<ul class="cmsep reset">
				<li>Groupe: {group-name}</li>
				<li>ICQ: {icq}</li>
			</ul>
		</div>
		<div class="clr"></div>
	</div>
	<div class="cominfo"><div class="dpad">
		[not-group=5]
		<div class="comedit">
			<div class="selectmass">{mass-action}</div>
			<ul class="reset">
				<li>[spam]Spam[/spam]</li>
				<li>[complaint]Plainte[/complaint]</li>
				<li>[com-edit]Editer[/com-edit]</li>
				<li>[com-del]Supprimer[/com-del]</li>
			</ul>
		</div>
		[/not-group]
		<ul class="cominfo reset">
			<li>Inscription: {registration}</li>
			<li>Statut: [online]<img src="{THEME}/images/online.png" style="vertical-align: middle;" title="Utilisateur en ligne" alt="Utilisateur en ligne" />[/online][offline]<img src="{THEME}/images/offline.png" style="vertical-align: middle;" title="Utilisateur hors ligne" alt="Utilisateur hors ligne" />[/offline]</li>
			<li>[declination={comm-num}]Commentaire|й|я|ев[/declination]</li>
			<li>[declination={news-num}]Publication|я|и|й[/declination]</li>
		</ul>
	</div>
	<span class="thide">^</span>
	</div>
	<div class="dcont">
		<h3 style="margin-bottom: 0.4em;">{news_title}</h3>
		{comment}
		[signature]<br clear="all" /><div class="signature">--------------------</div><div class="slink">{signature}</div>[/signature]
		<div class="clr"></div>
	</div>
</div>
[/fullresult]
[shortresult]
<div class="dpad searchitem">
	<h3 style="margin-bottom: 0.4em;">{news_title}</h3>
	<b>{date}</b> | Auteur: {author}
</div>
[/shortresult]
[/searchcomments]