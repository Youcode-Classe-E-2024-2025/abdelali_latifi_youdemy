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
    <header class="bg-blue-600 text-white p-4">
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
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
<section id="categories-section" class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Gestion des catégories</h2>
    <!-- Formulaire pour ajouter une catégorie -->
    <form action="" method="POST" class="mb-4">
        <label for="category_name" class="block font-medium">Nom de la catégorie :</label>
        <input type="text" name="category_name" id="category_name" class="border p-2 rounded w-full mb-2" required>
        <button type="submit" name="add_category" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ajouter la catégorie</button>
    </form>
    <!-- Affichage des catégories existantes avec un bouton de suppression -->
    <h3 class="font-medium mb-2">Catégories existantes :</h3>
    <ul class="space-y-2">
        <?php
        // Récupération des catégories depuis la base de données
        $categories = $admin->getCategories(); // Appeler une méthode pour récupérer les catégories
        if (!empty($categories)) {
            foreach ($categories as $category) {
                echo "<li class='flex justify-between items-center'>
                        <span>{$category['name']}</span>
                        <form action='' method='POST' class='inline'>
                            <input type='hidden' name='category_id' value='{$category['category_id']}'>
                            <button type='submit' name='delete_category' class='bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600'>Supprimer</button>
                        </form>
                    </li>";
            }
        } else {
            echo "<li>Aucune catégorie disponible.</li>";
        }
        ?>
    </ul>

    <?php
    // Traitement du formulaire d'ajout de catégorie
    if (isset($_POST['add_category'])) {
        $admin->addCategory($_POST['category_name']);
        echo "<p class='text-green-500 mt-2'>La catégorie a été ajoutée avec succès.</p>";
    }

    if (isset($_POST['delete_category'])) {
        $admin->deleteCategory($_POST['category_id']);
        echo "<p class='text-green-500 mt-2'>La catégorie a été supprimée avec succès.</p>";
    }
    ?>
</section>

       <!-- Section Gestion des tags -->
<section id="tags-section" class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Gestion des tags</h2>
    <!-- Formulaire pour ajouter des tags en masse -->
    <form action="" method="POST">
        <label for="tags" class="block font-medium">Tags (séparés par des virgules) :</label>
        <textarea name="tags" id="tags" rows="3" class="border p-2 rounded w-full mb-2" required></textarea>
        <button type="submit" name="bulk_insert_tags" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Insérer en masse</button>
    </form>

    <!-- Affichage des tags existants avec un bouton de suppression -->
    <h3 class="font-medium mb-2">Tags existants :</h3>
    <ul class="space-y-2">
        <?php
        // Récupérer les tags depuis la base de données
        $tags = $admin->getTags(); // Appeler une méthode pour récupérer les tags
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                echo "<li class='flex justify-between items-center'>
                        <span>{$tag['name']}</span>
                        <form action='' method='POST' class='inline'>
                            <input type='hidden' name='tag_id' value='{$tag['tag_id']}'>
                            <button type='submit' name='delete_tag' class='bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600'>Supprimer</button>
                        </form>
                    </li>";
            }
        } else {
            echo "<li>Aucun tag disponible.</li>";
        }
        ?>
    </ul>

    <?php
    // Traitement du formulaire d'ajout de tags
    if (isset($_POST['bulk_insert_tags'])) {
        $admin->bulkInsertTags($_POST['tags']);
        echo "<p class='text-green-500 mt-2'>Les tags ont été ajoutés avec succès.</p>";
    }

    // Traitement du formulaire de suppression de tag
    if (isset($_POST['delete_tag'])) {
        $admin->deleteTag($_POST['tag_id']);
        echo "<p class='text-green-500 mt-2'>Le tag a été supprimé avec succès.</p>";
    }
    ?>
</section>


            <!-- Section Statistiques globales -->
            <section id="stats-section" class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-4">Statistiques globales</h2>
                <?php
                $stats = $admin->getGlobalStatistics();
                ?>
                <div>
                    <h3 class="font-medium">Résumé</h3>
                    <p>Total des cours : <span id="total-courses"><?php echo $stats['total_courses']; ?></span></p>

                    <h3 class="font-medium">Répartition par catégorie</h3>
                    <ul id="courses-by-category" class="list-disc pl-6">
                        <?php
                        if (!empty($stats['courses_by_category'])) {
                            foreach ($stats['courses_by_category'] as $category) {
                                echo "<li>{$category['category']} : {$category['course_count']} cours</li>";
                            }
                        } else {
                            echo "<li>Aucune catégorie trouvée.</li>";
                        }
                        ?>
                    </ul>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
