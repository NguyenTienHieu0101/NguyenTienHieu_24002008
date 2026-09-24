<?php
    class CartItem{
        public string $name; // tên sản phẩm
        public float $price; // giá thành
        public int $quantity; // số lượng

        // hàm dựng có đối số
        public function __construct(string $name,float $price,int $quantity){
            $this->name = $name;
            $this->price = $price;
            $this->quantity = $quantity;
        }

        // hàm tính thành tiền của sản phẩm
        public function getTotal(){
            return $this->price * $this->quantity;
        }
    }

    class ShoppingCart {
        public array $items;  // danh sách sản phẩm
        // hàm dựng không đối số
        public function __construct(){
            $this->items = [];
        }
        // hàm thêm sản phẩm
        public function addItem(CartItem $item){
            if ($item == NULL){
                return;
            }
            $this->items[] = $item;
        }
        // hàm xóa sản phẩm
        public function removeItem(string $name){
            if (strlen($name) == 0 || empty($name)){
                return;
            }
            if (count($this->items) == 0){
                echo "Giỏ hàng rỗng! <br>";
                return;
            }
            for ($i = 0; $i < count($this->items); $i++){
                if ($this->items[$i]->name == $name){
                    unset($this->items[$i]);
                    return;
                }
            }
            echo "Không có ". $name . " trong giỏ hàng. OK chưa! <br>";
        }
        // hàm tính tổng tiền của toàn bộ sản phẩm
        public function calculateTotal(){
            if (count($this->items) == 0){
                return 0;
            }
            $total = 0;
            foreach ($this->items as $its){
                $total += $its->getTotal();
            }
            return $total;
        }
        // hàm hiển thị thông tin sản phẩm
        // tên - đơn giá - số lượng - thành tiền - tổng tiền
        public function displayCart(){
            if (count($this->items) == 0){
                echo "Giỏ hàng rỗng! <br>";
                return;
            }
            foreach ($this->items as $its){
                echo "Tên: " . $its->name . " | Đơn giá: " . $its->price . " | Số lượng: " . $its->quantity . " | Thành tiền: " . $its->getTotal() . "đ" ;
                echo "<br>";
            }
            echo "\n";
            echo "Tổng tiền: " . $this->calculateTotal() . "đ <br>";
        }
    }
    /// TEST CASE
    // Định nghĩa hàm main
    function main() {
        echo "Chương trình PHP bắt đầu chạy từ đây!<br>";
        //
        $shoppingCart = new ShoppingCart();
        $shoppingCart->addItem(new CartItem("Thịt chó", 150000.0, 3));
        $shoppingCart->addItem(new CartItem("Mắm tôm", 25000, 2));
        $shoppingCart->addItem(new CartItem("Lá mơ", 10000, 1));
        $shoppingCart->addItem(new CartItem("Giềng", 8000, 2));

        $shoppingCart->displayCart();
        echo "Xóa Giềng <br>";
        $shoppingCart->removeItem("Giềng");
       
        $shoppingCart->displayCart();
    }

    // hàm main để thực thi
    main();
?>

<!-- Chương trình PHP bắt đầu chạy từ đây!
Tên: Thịt chó | Đơn giá: 150000 | Số lượng: 3 | Thành tiền: 450000đ
Tên: Mắm tôm | Đơn giá: 25000 | Số lượng: 2 | Thành tiền: 50000đ
Tên: Lá mơ | Đơn giá: 10000 | Số lượng: 1 | Thành tiền: 10000đ
Tên: Giềng | Đơn giá: 8000 | Số lượng: 2 | Thành tiền: 16000đ
Tổng tiền: 526000đ
Xóa Giềng
Tên: Thịt chó | Đơn giá: 150000 | Số lượng: 3 | Thành tiền: 450000đ
Tên: Mắm tôm | Đơn giá: 25000 | Số lượng: 2 | Thành tiền: 50000đ
Tên: Lá mơ | Đơn giá: 10000 | Số lượng: 1 | Thành tiền: 10000đ
Tổng tiền: 510000đ -->