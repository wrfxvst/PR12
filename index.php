<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин</title>
</head>
<body>
    <?php
        

        class Shop{
            private $name;
            private $description;
            private $category;
            private $price;
            private $imageUrl;
            private $stock;
            private $offer;

            public function __construct($name, $description, $category, $price, $imageUrl, $stock, $offer){
                $this->name = $name;
                $this->description = $description;
                $this->category = $category;
                $this->price = $price;
                $this->imageUrl = $imageUrl;
                $this->stock = $stock;
                $this->offer = $offer;
            }

                public function SayAboutMe(){
                echo "<br> Название: ".$this->getName(). "<br>". "Описание: ".$this->getDescription(). "<br>". "Категория: ". $this->getCategory(). "<br>".
                "Цена: ". $this->getPrice(). "<br>". "Количество на складе: ". $this->getStock(). "<br>". "Скидка: ". $this->getOffer(). "<br><br>"; 
                ?>
                <img src="<?php echo $this->getImageUrl() ?>" width="300px" height="300px">
                <?php
                }

                public function getName(){
                    return $this->name;
                }

                public function getDescription(){
                    return $this->description;
                }

                public function getCategory(){
                    return $this->category;
                }

                public function getPrice(){
                    return $this->price;
                }

                public function getImageUrl(){
                    return $this->imageUrl;
                }

                public function getStock(){
                    return $this->stock;
                }

                public function getOffer(){
                    return $this->offer;
                }





        }
                

        $str = file_get_contents("goods.json");
        $goods = json_decode($str, 1);
        $str = file_get_contents("users.json");
        $users = json_decode($str, 1);



        if (isset($_POST['login']) && isset($_POST['password'])) {
            foreach ($users as $user) {
                if ($user["login"] === $_POST['login'] && $user["password"] === $_POST['password']) {
                    $entered_user = $user;
                    break;
                }
            }
        }


        if(isset($entered_user['login'])) {
            ?>
                <h1> <?php echo " Добро пожаловать, " . $entered_user['login'];?> </h1>
                <form method="POST">
                    <input type="submit" value='Выйти'>
                </form>
            <?php
        } 
        else {
            ?>
                <form method="POST">
                        Логин: <br>
                    <input name="login" required><br>
                        Пароль: <br>
                    <input type="password" name="password" required><br>
                    <input type="submit" value="Войти">
                </form>
            <?php
                if (isset($_POST['login']) && isset($_POST['password'])) {
                    echo "Неверный логин или пароль";
                }
        } 

        if (isset($_POST["category_name"]) or isset($_POST["category_select"])) {
            if (isset($_POST["name"]) && isset($_POST["description"]) && isset($_POST["price"]) && isset($_POST["imageUrl"]) && isset($_POST["stock"]) && isset($_POST["offer"])) {
                $goods[] = ["name" => $_POST["name"], "description" => $_POST["description"], "category" => $_POST["category_name"] . $_POST["category_select"], "price" => $_POST["price"], "imageUrl" => $_POST["imageUrl"], "stock" => $_POST["stock"], "offer" => $_POST["offer"] ];
                $str = json_encode($goods);
                file_put_contents('goods.json', $str);
            }
        } 
          
        if (isset($_POST['delete_index'])) {
            unset($goods[$_POST['delete_index']]);
            $goods = array_values($goods);
            $str = json_encode($goods);
            file_put_contents('goods.json', $str);
        }

        $objgoods=[];
        $categories = [];

        foreach($goods as $good){
            $objgoods[]= new Shop ($good['name'], $good['description'], $good['category'], $good['price'], $good['imageUrl'], $good['stock'], $good['offer']);
            $categories[] = $good['category'];
        }
        $result_categories = array_unique($categories);

        if (isset($entered_user) && $entered_user['role'] == 'admin') {
        
    ?>
        
        <form method="POST">
            Введите название товара: <br>
            <input name="name" required> <br>
            Введите описание товара: <br>
            <textarea name="description" cols="30" rows="10" required></textarea> <br>
            Введите название категории: <br>
            <input name="category_name">
            Или
            <select name="category_select" >
                <option value=""> - </option>
                <option value="Грабли/лопаты"> Грабли/лопаты</option>
                <option value="Перчатки"> Перчатки</option>
                <option value="Для отдыха"> Для отдыха</option>
                <option value="Лейки"> Лейки</option>
            </select> <br>
            Введите цену: <br>
            <input name="price" required><br>
            Объем товара на складе: <br>
            <input name="stock" required> <br>
            Скидка: <br>
            <input name="offer"> <br>
            Введите URL картинки: <br>
            <input type="url" name="imageUrl"> <br>
            <input type="submit"> <br>
        </form>
    
    <?php
        }

        foreach ($result_categories as $category) {
            ?>
                <h2><?php echo $category?></h2>
            <?php
                foreach ($objgoods as $key => $good) {
                    if ($good->getCategory() == $category){
                        $good->SayAboutMe();
                        if (isset($entered_user) && $entered_user['role'] == 'admin') {
            ?> 
                            <form action="" method="POST">
                                <button type="submit" name="delete_index" value="<?= $key ?>">Удалить</button>
                            </form>
            <?php
                        }
                        if (isset($entered_user) && $entered_user['role'] == 'user') {
            ?>
                            <form action="" method="POST">
                                <button type="submit" name="favorite_index" value="<?= $key ?>">Добавить в избранное</button>
                            </form>
            <?php
                        }
                    }
                }
        }
            ?>

</body>
</html>