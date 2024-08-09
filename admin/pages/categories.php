<?php
require_once "../components/header.php";
require_once "../components/aside.php";
require_once "../configuration/functions.php";

if (isset($_GET['id'])) {
	deleteCategory($_GET['id']);
}
?>
    <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
        <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                <h1 class="text-3xl text-black pb-6">Categories</h1>
                <div class="w-full mt-6">
                    <div class="bg-white overflow-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">ID</th>
                                <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Nomi uz</th>
                                <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Nomi ru</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm"></th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-700">
														<?php
														$categories = getAllCategories();
														for ($i = 0; $i < (count($categories) % 2 === 0 ? count($categories) : count($categories) - 1); $i += 2) {
															?>
                                <tr>
                                    <td class="w-1/3 text-left py-3 px-4"><?= $categories[$i]['id']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= $categories[$i]['uz']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= $categories[$i]['ru']; ?></td>
                                    <td class="text-left py-3 px-4">
                                        <a href="categories.php?id=<?= $categories[$i]['id']; ?>"
                                           class="bg-red-500 text-white hover:bg-red-600 duration-100 rounded border-0 py-2 px-4">
                                            O'chirish
                                        </a>
                                    </td>
                                </tr>
                                <tr class="bg-gray-200">
                                    <td class="w-1/3 text-left py-3 px-4"><?= $categories[$i + 1]['id']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= $categories[$i + 1]['uz']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= $categories[$i + 1]['ru']; ?></td>
                                    <td class="text-left py-3 px-4">
                                        <a href="categories.php?id=<?= $categories[$i + 1]['id']; ?>"
                                           class="bg-red-500 text-white hover:bg-red-600 duration-100 rounded border-0 py-2 px-4">
                                            O'chirish
                                        </a>
                                    </td>
                                </tr>
														<?php }
														if (count($categories) % 2 === 1) { ?>
                                <tr>
                                    <td class="w-1/3 text-left py-3 px-4"><?= end($categories)['id']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= end($categories)['uz']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= end($categories)['ru']; ?></td>
                                    <td class="text-left py-3 px-4">
                                        <a href="categories.php?id=<?= end($categories)['id']; ?>"
                                           class="bg-red-500 text-white hover:bg-red-600 duration-100 rounded border-0 py-2 px-4">
                                            O'chirish
                                        </a>
                                    </td>
                                </tr>
														<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
<?php require_once "../components/footer.php"; ?>