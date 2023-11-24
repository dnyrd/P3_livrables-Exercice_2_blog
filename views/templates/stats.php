<?php 
/**
 * Affichage des statistiques des articles avec tableau triable.
 */
?>

<h2>Statistiques des articles</h2>

<div class="statsContainer">
    <table class="statsTable" id="statsTable">
        <thead>
            <tr>
                <th data-column="title" data-order="asc">
                    Titre <span class="sortIndicator">▼</span>
                </th>
                <th data-column="views" data-order="desc">
                    Vues <span class="sortIndicator"></span>
                </th>
                <th data-column="comment_count" data-order="desc">
                    Commentaires <span class="sortIndicator"></span>
                </th>
                <th data-column="date_creation" data-order="desc">
                    Date de publication <span class="sortIndicator"></span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stats as $index => $stat) { 
                $rowClass = $index % 2 === 0 ? 'evenRow' : 'oddRow';
                // Conversion de la date en format français
                $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $stat['date_creation']);
                $dateFormatted = $dateObj ? Utils::convertDateToFrenchFormat($dateObj) : $stat['date_creation'];
            ?>
                <tr class="<?= $rowClass ?>" data-id="<?= $stat['id'] ?>">
                    <td class="titleCell"><?= Utils::format($stat['title']) ?></td>
                    <td class="numberCell" data-value="<?= $stat['views'] ?>"><?= $stat['views'] ?></td>
                    <td class="numberCell" data-value="<?= $stat['comment_count'] ?>"><?= $stat['comment_count'] ?></td>
                    <td class="dateCell" data-value="<?= $stat['date_creation'] ?>"><?= $dateFormatted ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="adminActions">
    <a class="submit" href="index.php?action=admin">Retour à l'administration</a>
</div>

<style>
/* Styles spécifiques pour la page des statistiques */
.statsContainer {
    width: 100%;
    margin-bottom: 30px;
    overflow-x: auto;
}

.statsTable {
    width: 100%;
    min-width: 1366px;
    border-collapse: collapse;
    background-color: white;
}

.statsTable thead {
    background-color: var(--headerColor);
    color: white;
}

.statsTable th {
    padding: 15px 20px;
    text-align: left;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    user-select: none;
    position: relative;
}

.statsTable th:hover {
    background-color: var(--titleColor);
}

.sortIndicator {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
}

.statsTable tbody tr {
    border-bottom: 1px solid #ddd;
    transition: background-color 0.2s;
}

.statsTable tbody tr:hover {
    background-color: var(--headerPaleColor);
}

.statsTable tbody tr.evenRow {
    background-color: var(--backgroundColor);
}

.statsTable tbody tr.oddRow {
    background-color: white;
}

.statsTable td {
    padding: 15px 20px;
    font-size: 15px;
}

.titleCell {
    font-weight: bold;
    color: var(--headerColor);
}

.numberCell {
    text-align: center;
    color: var(--dateColor);
    font-weight: bold;
}

.dateCell {
    color: var(--dateColor);
    font-style: italic;
}

.adminActions {
    margin-top: 20px;
}
</style>

<script>
// Script de tri du tableau sans librairie tierce
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('statsTable');
    const headers = table.querySelectorAll('th');
    const tbody = table.querySelector('tbody');

    // Fonction pour trier le tableau
    function sortTable(columnIndex, columnName, order) {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            let aValue, bValue;
            
            // Récupération des valeurs selon le type de colonne
            if (columnName === 'title') {
                aValue = a.querySelector('.titleCell').textContent.trim().toLowerCase();
                bValue = b.querySelector('.titleCell').textContent.trim().toLowerCase();
            } else if (columnName === 'views' || columnName === 'comment_count') {
                aValue = parseInt(a.querySelectorAll('td')[columnIndex].dataset.value);
                bValue = parseInt(b.querySelectorAll('td')[columnIndex].dataset.value);
            } else if (columnName === 'date_creation') {
                aValue = new Date(a.querySelector('.dateCell').dataset.value);
                bValue = new Date(b.querySelector('.dateCell').dataset.value);
            }

            // Comparaison
            if (aValue < bValue) return order === 'asc' ? -1 : 1;
            if (aValue > bValue) return order === 'asc' ? 1 : -1;
            return 0;
        });

        // Réorganisation du tableau
        rows.forEach(row => tbody.appendChild(row));
        
        // Réapplication des classes evenRow/oddRow
        rows.forEach((row, index) => {
            row.classList.remove('evenRow', 'oddRow');
            row.classList.add(index % 2 === 0 ? 'evenRow' : 'oddRow');
        });
    }

    // Gestion des clics sur les en-têtes
    headers.forEach((header, index) => {
        header.addEventListener('click', function() {
            const column = this.dataset.column;
            const currentOrder = this.dataset.order;
            const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
            
            // Réinitialisation de tous les indicateurs
            headers.forEach(h => {
                h.dataset.order = h === this ? newOrder : (h.dataset.column === 'title' ? 'asc' : 'desc');
                h.querySelector('.sortIndicator').textContent = '';
            });
            
            // Affichage de l'indicateur de tri
            this.querySelector('.sortIndicator').textContent = newOrder === 'asc' ? '▲' : '▼';
            this.dataset.order = newOrder;
            
            // Tri du tableau
            sortTable(index, column, newOrder);
        });
    });
});
</script>
