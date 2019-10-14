<div class="container-creation" style="text-align: center;">
    <form action="/movies" method="POST">
        <p>
            <p class="label creation-text-align">Название</p>
            <br/>
            <input
                class="input-creation"
                type="text"
                name="Name"
                value="<?= $this->escape($params['Name'] ?? '') ?>"
            />
        </p>
        <p>
            <p class="label creation-text-align">Формат</p>
            <br/>
            <select class="input-creation" name="Format" >
                <?php $format = $params['Format'] ?? '' ?>
                <option value="VHS" <?= $format == 'VHS' ? 'selected' : '' ?> >VHS</option>
                <option value="DVD" <?= $format == 'DVD' ? 'selected' : '' ?> >DVD</option>
                <option value="Blu-Ray" <?= $format == 'Blu-Ray' ? 'selected' : '' ?> >Blu-Ray</option>
            </select>
        </p>
        <p>
            <p class="label creation-text-align">Год выпуска</p>
            <br/>
            <input
                class="input-creation"
                type="text"
                name="ReleaseYear"
                value="<?= $this->escape($params['ReleaseYear'] ?? '') ?>"
            />
        </p>
        <p>
            <p class="label creation-text-align">Актеры</p>
            <br/>
            <div id="actorsContainer">
                <?php $actors = $params['Actors'] ?? [] ?>
                <?php foreach ($actors as $actorName) : ?>
                    <div class="actor-block">
                        <button type="button"  class="btn btn-light remove-actor">Удалить</button>
                        <br/>
                        <div>
                            <input
                                class="input-creation"
                                value="<?= $this->escape($actorName) ?>"
                                type="text"
                                name="Actors[]"
                            />
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button id="addActor" type="button" class="btn btn-light input-center">Добавить актера</button>
        </p>
        <p>
            <input class="btn btn-light input-center input-save" type="submit" value="Сохранить" />
        </p>
    </form>
</div>

<script type="text/javascript">
    $('#addActor').click(function () {
        $('#actorsContainer').append(`
            <div class="actor-block">
                <button class="btn btn-light remove-actor">Удалить</button>
                <br/>
                <div>
                    <input
                        class="input-creation"
                        type="text"
                        name="Actors[]"
                    />
                </div>
            </div>
        `);
    });

    $(document).on('click', '.remove-actor', function () {
        $(this).closest('.actor-block').remove();
    });
</script>