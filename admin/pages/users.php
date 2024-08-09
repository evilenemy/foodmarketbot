<?php
require_once "../components/header.php";
require_once "../components/aside.php";
require_once "../configuration/functions.php";
?>
    <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
        <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                <h1 class="text-3xl text-black pb-6">Users</h1>
                <div class="w-full mt-6">
                    <div class="bg-white overflow-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">ID</th>
                                <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">ChatID</th>
                                <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Referrallar soni
                                </th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-700">
														<?php
														$users = getAllUsers();
														for ($i = 0; $i < (count($users) % 2 === 0 ? count($users) : count($users) - 1); $i += 2) {
															?>
                                <tr>
                                    <td class="w-1/3 text-left py-3 px-4"><?= $users[$i]['id']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= $users[$i]['chatID']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= count(json_decode($users[$i]['referrals'], true)); ?></td>
                                </tr>
                                <tr class="bg-gray-200">
                                    <td class="w-1/3 text-left py-3 px-4"><?= $users[$i + 1]['id']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= $users[$i + 1]['chatID']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= count(json_decode($users[$i + 1]['referrals'], true)); ?></td>
                                </tr>
														<?php }
														if (count($users) % 2 === 1) { ?>
                                <tr>
                                    <td class="w-1/3 text-left py-3 px-4"><?= end($users)['id']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= end($users)['chatID']; ?></td>
                                    <td class="w-1/3 text-left py-3 px-4"><?= count(json_decode(end($users)['referrals'], true)); ?></td>
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