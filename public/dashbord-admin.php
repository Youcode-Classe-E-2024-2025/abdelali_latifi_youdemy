<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="bg-blue-600 text-white p-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Admin Dashboard</h1>
    
    <!-- Bouton de Logout -->
    <form action="../backend/athentification/logout.php" method="POST" class="inline">
        <button type="submit" name="logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">
            Logout
        </button>
    </form>
</header>


    <div class="flex">
        <!-- Aside (Menu latéral) -->
        <aside class="bg-gray-200 w-64 p-4 h-screen">
            <h2 class="text-lg font-semibold mb-4">Navigation rapide</h2>
            <ul class="space-y-2">
                <li><a href="../public/course-manager.php" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Gestion des cours</a></li>
                <li><a href="#categories-section" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Gestion des catégories</a></li>
                <li><a href="#tags-section" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Gestion des tags</a></li>
                <li><a href="#stats-section" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Statistiques globales</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="p-6 flex-1 space-y-8">
            <?php
            require_once '../backend/admin.php';
            $admin = new Admin();
            ?>

            <!-- Section Gestion des utilisateurs -->
            <section id="users-section" class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Gestion des enseignants</h2>
    
    <!-- Afficher les enseignants -->
    <h3 class="font-medium mb-4">Liste des enseignants :</h3>
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 p-2">ID</th>
                <th class="border border-gray-300 p-2">Nom</th>
                <th class="border border-gray-300 p-2">Email</th>
                <th class="border border-gray-300 p-2">Statut</th>
                <th class="border border-gray-300 p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Récupérer les enseignants depuis la base de données
            $teachers = $admin->getTeachers(); // Implémentez cette méthode dans votre classe Admin
            if (!empty($teachers)) {
                foreach ($teachers as $teacher) {
                    echo "<tr>
                        <td class='border border-gray-300 p-2'>{$teacher['user_id']}</td>
                        <td class='border border-gray-300 p-2'>{$teacher['name']}</td>
                        <td class='border border-gray-300 p-2'>{$teacher['email']}</td>
                        <td class='border border-gray-300 p-2'>" . ($teacher['is_active'] ? 'Actif' : 'Suspendu') . "</td>
                        <td class='border border-gray-300 p-2'>
                            <!-- Formulaire pour modifier le statut -->
                            <form action='' method='POST' class='inline'>
                                <input type='hidden' name='user_id' value='{$teacher['user_id']}'>
                                <select name='is_active' class='border p-2 rounded'>
                                    <option value='1'" . ($teacher['is_active'] ? ' selected' : '') . ">Activer</option>
                                    <option value='0'" . (!$teacher['is_active'] ? ' selected' : '') . ">Suspendre</option>
                                </select>
                                <button type='submit' name='update_user_status' class='bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600'>Mettre à jour</button>
                            </form>
                            <!-- Formulaire pour supprimer -->
                            <form action='' method='POST' class='inline'>
                                <input type='hidden' name='user_id' value='{$teacher['user_id']}'>
                                <button type='submit' name='delete_teacher' class='bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600'>Supprimer</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center border border-gray-300 p-2'>Aucun enseignant trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Traitement du formulaire de mise à jour du statut
    if (isset($_POST['update_user_status'])) {
        $admin->updateUserStatus($_POST['user_id'], $_POST['is_active']);
        echo "<p class='text-green-500 mt-2'>Le statut de l'utilisateur a été mis à jour.</p>";
    }

    // Traitement du formulaire de suppression
    if (isset($_POST['delete_teacher'])) {
        $admin->deleteTeacher($_POST['user_id']);
        echo "<p class='text-red-500 mt-2'>L'enseignant a été supprimé avec succès.</p>";
    }
    ?>
</section>

 <!-- Section Gestion des catégories -->
<!-- Section Gestion des catégories -->
<section id="categories-section" class="bg-white p-4 rounded-lg shadow-lg mb-8">
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Gestion des catégories</h2>
    <!-- Formulaire pour ajouter une catégorie -->
    <form action="" method="POST" class="mb-6">
        <label for="category_name" class="block font-medium text-gray-700 mb-2">Nom de la catégorie :</label>
        <input type="text" name="category_name" id="category_name" class="border p-3 rounded w-full mb-4 focus:ring-2 focus:ring-blue-500" required>
        <button type="submit" name="add_category" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">Ajouter la catégorie</button>
    </form>

    <!-- Affichage des catégories existantes avec un bouton de suppression -->
    <h3 class="font-medium mb-2 text-gray-800">Catégories existantes :</h3>
    <div class="max-h-72 overflow-y-auto space-y-4">
        <?php
        $categories = $admin->getCategories();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                echo "<li class='flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md'>
                        <span class='text-lg text-gray-800'>{$category['name']}</span>
                        <form action='' method='POST' class='inline'>
                            <input type='hidden' name='category_id' value='{$category['category_id']}'>
                            <button type='submit' name='delete_category' class='bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400'>Supprimer</button>
                        </form>
                    </li>";
            }
        } else {
            echo "<li class='text-gray-500'>Aucune catégorie disponible.</li>";
        }
        ?>
    </div>

    <?php
    if (isset($_POST['add_category'])) {
        $admin->addCategory($_POST['category_name']);
        echo "<p class='text-green-500 mt-4'>La catégorie a été ajoutée avec succès.</p>";
    }

    if (isset($_POST['delete_category'])) {
        $admin->deleteCategory($_POST['category_id']);
        echo "<p class='text-red-500 mt-4'>La catégorie a été supprimée avec succès.</p>";
    }
    ?>
</section>

<!-- Section Gestion des tags -->
<section id="tags-section" class="bg-white p-4 rounded-lg shadow-lg mb-8">
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Gestion des tags</h2>
    <!-- Formulaire pour ajouter des tags en masse -->
    <form action="" method="POST" class="mb-6">
        <label for="tags" class="block font-medium text-gray-700 mb-2">Tags (séparés par des virgules) :</label>
        <textarea name="tags" id="tags" rows="3" class="border p-3 rounded w-full mb-4 focus:ring-2 focus:ring-blue-500" required></textarea>
        <button type="submit" name="bulk_insert_tags" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">Insérer en masse</button>
    </form>

    <!-- Affichage des tags existants avec un bouton de suppression -->
    <h3 class="font-medium mb-2 text-gray-800">Tags existants :</h3>
    <div class="max-h-72 overflow-y-auto space-y-4">
        <?php
        $tags = $admin->getTags();
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                echo "<li class='flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md'>
                        <span class='text-lg text-gray-800'>{$tag['name']}</span>
                        <form action='' method='POST' class='inline'>
                            <input type='hidden' name='tag_id' value='{$tag['tag_id']}'>
                            <button type='submit' name='delete_tag' class='bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400'>Supprimer</button>
                        </form>
                    </li>";
            }
        } else {
            echo "<li class='text-gray-500'>Aucun tag disponible.</li>";
        }
        ?>
    </div>

    <?php
    if (isset($_POST['bulk_insert_tags'])) {
        $admin->bulkInsertTags($_POST['tags']);
        echo "<p class='text-green-500 mt-4'>Les tags ont été ajoutés avec succès.</p>";
    }

    if (isset($_POST['delete_tag'])) {
        $admin->deleteTag($_POST['tag_id']);
        echo "<p class='text-red-500 mt-4'>Le tag a été supprimé avec succès.</p>";
    }
    ?>
</section>


            <!-- Section Statistiques globales -->
            <section id="stats-section" class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Statistiques globales</h2>
    <?php
    $stats = $admin->getGlobalStatistics();
    ?>
    <div class="space-y-6">
        <!-- Résumé -->
        <div class="bg-gray-50 p-4 rounded-lg shadow-md">
            <h3 class="font-medium text-gray-700 mb-2">Résumé des statistiques</h3>
            <div class="flex items-center space-x-4">
                <div class="text-3xl font-semibold text-indigo-600">
                    <?php echo $stats['total_courses']; ?>
                </div>
                <span class="text-lg text-gray-600">Total des cours</span>
            </div>
        </div>

        <!-- Répartition par catégorie -->
        <div class="bg-gray-50 p-4 rounded-lg shadow-md">
            <h3 class="font-medium text-gray-700 mb-2">Répartition par catégorie</h3>
            <?php
            if (!empty($stats['courses_by_category'])) {
                foreach ($stats['courses_by_category'] as $category) {
                    echo "
                    <div class='flex items-center justify-between mb-2'>
                        <span class='text-gray-700 font-medium'>{$category['category']}</span>
                        <span class='text-gray-500'>{$category['course_count']} cours</span>
                        <span class='bg-indigo-500 text-white text-xs px-2 py-1 rounded-full'>
                            {$category['course_count']}
                        </span>
                    </div>";
                }
            } else {
                echo "<p class='text-gray-500'>Aucune catégorie trouvée.</p>";
            }
            ?>
        </div>
    </div>
</section>

        </main>
    </div>
</body>
</html>
