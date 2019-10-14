<div class="searchContainer">
    <form action="/movies" method="GET">
        <p class="label">Поиск по названию</p>
        <br>
        <input 
            type="text" 
            class="search" 
            name="SearchByMovieName" 
            value="<?= $this->escape($searchByMovieName) ?>"
        />
        <br>
        <p class="label">Поиск по актерам</p>
        <br>
        <input 
            type="text" 
            class="search" 
            name="SearchByActorName" 
            value="<?= $this->escape($searchByActorName) ?>"
        />
        <br>
        <input 
            class="btn btn-secondary" 
            type="submit" 
            value="Искать" />
    </form>
</div>

<table class="table-list">
    <tr>
        <th>Название</th>
        <th>Актеры</th>
        <th>Опции</th>
    </tr>
    <?php foreach ($movies as $movie): ?>
        <tr>
            <td> <?= $this->escape($movie['Name']) ?> </td>
            <td>
                <?= $this->escape(implode(', ', $movie["Actors"])) ?>
            </td>
            <td>
                <a class="show-link" href="/movies/<?= $movie['Id'] ?>"><button class="btn btn-light show-link">Показать</button></a>

                <form action="/movies/<?= $movie['Id'] ?>">
                    <input type='hidden' name="RequestMethod" value="DELETE" />
                    <input class="btn btn-light" type="submit" onclick="return confirm('Действительно удалить выбранный фильм?')" value="Удалить" />
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>