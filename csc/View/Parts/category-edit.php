<h2>Редактирование списка категорий</h2>

<form action="<?php echo SCL_URL ?>" method="post">

    <label class="choose left"><h3><input type="radio" name="category-edit" value="new" checked="checked"> Добавить новую</h3></label>
    <label class="choose right"><h3><input type="radio" name="category-edit" value="old"> Изменить имеющуюся</h3></label>

    <div id="category-new">

        <fieldset>
            <legend>1. Укажите раздел</legend>

            <label id="check-optgroup-label"><p><input type="checkbox" name="new-optgroup" class="checkbox" id="check-optgroup"> Создать раздел (если подходящего раздела не найдено)</p></label>

            <div id="current-optgroup">
                <select name="parent-category-id">
                    <?php echo make_optgroup_list($categories); ?>
                </select>
            </div>

            <div id="new-optgroup">
                <input class="input-text" type="text" name="new-optgroup-name">
            </div>
        </fieldset>

        <fieldset>
            <legend>2. Введите имя новой категории</legend>
            <input class="input-text" type="text" name="new-category">
        </fieldset>

    </div>

    <div id="category-old">

        <fieldset>
            <legend>1. Выберите категорию, имя которой хотите изменить</legend>
            <select name="current-category-id">
                <?php echo make_category_list($categories); ?>
            </select>
        </fieldset>

        <fieldset>
            <legend>2. Введите новое имя для выбранной категории</legend>
            <input class="input-text" type="text" name="new-name">
        </fieldset>
    </div>

    <input id="submit-category" class="button" type="submit" value="Отправить">

</form>
