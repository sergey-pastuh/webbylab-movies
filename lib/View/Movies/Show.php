<div class="container">
	<div class="movie-title">
		<?= $this->escape($movie['Name']) ?>
	</div>	
	<hr>
	<div class="movie-info">
		<p>
			Год выпуска: <?=$this->escape($movie['ReleaseYear'])?>	
		</p> 
		<br>
		<p>
			Формат: <?= $this->escape($movie['Format']) ?> 
		</p> 
		<br>
		<p>
			Актёры: <?= $this->escape(implode(', ', $movie["Actors"])) ?> 
		</p> 
		<br>
	</div>
</div>