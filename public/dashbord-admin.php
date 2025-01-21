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
                <li><a href="#users-section" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Gestion des utilisateurs</a></li>
                <li><a href="#courses-section" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Gestion des cours</a></li>
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
                <h2 class="text-xl font-semibold mb-4">Gestion des utilisateurs</h2>
                <form action="" method="POST" class="mb-4">
                    <label for="teacher_id" class="block font-medium">ID de l'enseignant à valider :</label>
                    <input type="number" name="teacher_id" id="teacher_id" class="border p-2 rounded w-full mb-2" required>
                    <button type="submit" name="validate_teacher" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Valider</button>
                </form>

                <form action="" method="POST">
                    <label for="user_id" class="block font-medium">ID de l'utilisateur :</label>
                    <input type="number" name="user_id" id="user_id" class="border p-2 rounded w-full mb-2" required>

                    <label for="is_active" class="block font-medium">Statut :</label>
                    <select name="is_active" id="is_active" class="border p-2 rounded w-full mb-2">
                        <option value="1">Activer</option>
                        <option value="0">Suspendre</option>
                    </select>

                    <button type="submit" name="update_user_status" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Mettre à jour</button>
                </form>

                <?php
                if (isset($_POST['validate_teacher'])) {
                    $admin->validateTeacher($_POST['teacher_id']);
                    echo "<p class='text-green-500 mt-2'>L'enseignant a été validé avec succès.</p>";
                }

                if (isset($_POST['update_user_status'])) {
                    $admin->updateUserStatus($_POST['user_id'], $_POST['is_active']);
                    echo "<p class='text-green-500 mt-2'>Le statut de l'utilisateur a été mis à jour.</p>";
                }
                ?>
            </section>

            <!-- Section Gestion des cours -->
            <section id="courses-section" class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-4">Gestion des cours</h2>
                <form action="" method="POST">
                    <label for="course_id" class="block font-medium">ID du cours :</label>
                    <input type="number" name="course_id" id="course_id" class="border p-2 rounded w-full mb-2" required>
                    <button type="submit" name="delete_course" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Supprimer le cours</button>
                </form>

                <?php
                if (isset($_POST['delete_course'])) {
                    $admin->deleteCourse($_POST['course_id']);
                    echo "<p class='text-green-500 mt-2'>Le cours a été supprimé avec succès.</p>";
                }
                ?>
            </section>

            <!-- Section Gestion des catégories -->
            <section id="categories-section" class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-4">Gestion des catégories</h2>
                <form action="" method="POST" class="mb-4">
                    <label for="category_name" class="block font-medium">Nom de la catégorie :</label>
                    <input type="text" name="category_name" id="category_name" class="border p-2 rounded w-full mb-2" required>
                    <button type="submit" name="add_category" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ajouter la catégorie</button>
                </form>

                <form action="" method="POST">
                    <label for="category_id" class="block font-medium">ID de la catégorie :</label>
                    <input type="number" name="category_id" id="category_id" class="border p-2 rounded w-full mb-2" required>
                    <button type="submit" name="delete_category" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Supprimer la catégorie</button>
                </form>

                <?php
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
                <form action="" method="POST">
                    <label for="tags" class="block font-medium">Tags (séparés par des virgules) :</label>
                    <textarea name="tags" id="tags" rows="3" class="border p-2 rounded w-full mb-2" required></textarea>
                    <button type="submit" name="bulk_insert_tags" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Insérer en masse</button>
                </form>

                <?php
                if (isset($_POST['bulk_insert_tags'])) {
                    $admin->bulkInsertTags($_POST['tags']);
                    echo "<p class='text-green-500 mt-2'>Les tags ont été ajoutés avec succès.</p>";
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

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center p-4 mt-8">
        <p>&copy; 2025 Youdemy. Tous droits réservés.</p>
    </footer>
</body>
</html>
