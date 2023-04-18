<?php
include "path.php";
include "app/controllers/topics.php";

$page = isset($_GET['page']) ? $_GET['page']: 1;
$limit = 3;
$offset = $limit * ($page - 1);


$posts = selectAllTest('posts', ['id_topic' => $_GET['id']], $limit, $offset);
$total_posts = countAll('posts', ['id_topic' => $_GET['id']]);

// Розраховуємо загальну кількість сторінок пагінації
$total_pages = ceil($total_posts / $limit);

$topTopic = selectTopTopicFromPostsOnIndex('posts');
$category = selectOne('topics', ['id' => $_GET['id']]);

?>

<!doctype html>
<html lang="ua">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
          integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Custom Styling -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title><?=$category['name']; ?></title>
</head>
<body>

<?php include("app/include/header.php");
$pagination = '';
if ($total_pages > 1) {
    $pagination .= '<nav aria-label="Page navigation example">';
    $pagination .= '<ul class="pagination justify-content-center">';
    $pagination .= '<li class="page-item"><a class="page-link" href="./category.php?id='.$_GET['id'].'&page=1">First</a></li>';
    if ($page > 1) {
        $pagination .= '<li class="page-item"><a class="page-link" href="./category.php?id='.$_GET['id'].'&page='.($page-1).'">Prev</a></li>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $page) {
            $pagination .= '<li class="page-item active"><a class="page-link" href="./category.php?id='.$_GET['id'].'&page='.$i.'">'.$i.'</a></li>';
        } else {
            $pagination .= '<li class="page-item"><a class="page-link" href="./category.php?id='.$_GET['id'].'&page='.$i.'">'.$i.'</a></li>';
        }
    }
    if ($page < $total_pages) {
        $pagination .= '<li class="page-item"><a class="page-link" href="./category.php?id='.$_GET['id'].'&page='.($page+1).'">Next</a></li>';
    }
    $pagination .= '<li class="page-item"><a class="page-link" href="./category.php?id='.$_GET['id'].'&page='.$total_pages.'">Last</a></li>';
    $pagination .= '</ul>';
    $pagination .= '</nav>';
}
?>

<!-- блок main-->
<div class="container">
    <div class="content row">
        <!-- Main Content -->
        <div class="main-content col-md-9 col-12">
            <h2>Публікації з розділа <strong><?=$category['name']; ?></strong></h2>
            <?php echo $pagination;?>
            <?php foreach ($posts as $post): ?>
                <div class="post row">
                    <div class="img col-12 col-md-3">
                        <img src="<?= './assets/images/posts/' . $post['img'] ?>" alt="<?=$post['title']?>" class="img-thumbnail">
                    </div>
                    <div class="post_text col-12 col-md-8">
                        <h3>
                            <a href="<?= './single.php?post=' . $post['id'];?>"><?=substr($post['title'], 0, 80) . '...'  ?></a>
                        </h3>
                        <i class="far fa-calendar"> <?=$post['created_data'];?></i>
                        <p class="preview-text">

                            <?=mb_substr($post['content'], 0, 55, 'UTF-8'). '...'  ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- sidebar Content -->
        <div class="sidebar col-md-3 col-12">

            <div class="section search">
                <h3>Пошук</h3>
                <form action="search.php" method="post">
                    <input type="text" name="search-term" class="text-input" placeholder="Введіть слово...">
                </form>
            </div>


            <div class="section topics">
                <h3>Категорії</h3>
                <ul>
                    <?php foreach ($topics as $key => $topic): ?>
                        <li>
                            <a href="<?= './category.php?id=' . $topic['id'] . '?page=1'; ?>"><?=$topic['name']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- блок main END-->
<!-- footer -->
<?php include("app/include/footer.php"); ?>
<!-- // footer -->


<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

</body>
</html>