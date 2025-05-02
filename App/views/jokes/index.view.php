<?php
/**
 * FILE TITLE GOES HERE
 *
 * DESCRIPTION OF THE PURPOSE AND USE OF THE CODE
 * MAY BE MORE THAN ONE LINE LONG
 * KEEP LINE LENGTH TO NO MORE THAN 96 CHARACTERS
 *
 * Filename:        index.view.php
 * Location:        ${FILE_LOCATION}
 * Project:         SaaS-Vanilla-MVC
 * Date Created:    20/08/2024
 *
 * Author:          Adrian Gould <Adrian.Gould@nmtafe.wa.edu.au>
 *
 */

/* Load HTML header and navigation areas */
loadPartial("header");
loadPartial('navigation');

?>

<h1>Jokes List</h1>

<form method="get" action="/jokes">
    <input type="text" name="search" placeholder="Search joke body..." value="<?= htmlspecialchars($search ?? '') ?>">
    <button type="submit">Search</button>
</form>
    <ul>
        <?php if (isset($jokes) && count($jokes) > 0): ?>
            <?php foreach ($jokes as $joke): ?>
                <li>
                    <!-- Access object properties with -> syntax -->
                    <h2><a href="/jokes/<?= $joke->id ?>"><?= htmlspecialchars($joke->title) ?></a></h2>
                    <p><strong>Category:</strong> <?= htmlspecialchars($joke->category_name) ?></p>
                    <p><strong>Author:</strong> <?= htmlspecialchars($joke->nickname) ?></p>
                    <p><strong>Tags:</strong> <?= htmlspecialchars($joke->tags ?? 'None') ?></p>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No jokes found.</p>
        <?php endif; ?>
    </ul>
<?php
loadPartial("footer");