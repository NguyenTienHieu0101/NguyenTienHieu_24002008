window.TheSanPham = {
  props: {
    ten: {
      type: String,
      required: true
    },
    gia: {
      type: Number,
      required: true
    },
    anh: {
      type: String,
      default: ''
    },
    moTa: {
      type: String,
      default: 'Chưa có mô tả cho sản phẩm này.'
    },
    soLuongTon: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      moTaDangMo: false
    };
  },
  methods: {
    formatCurrency(amount) {
      return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        maximumFractionDigits: 0
      }).format(amount);
    }
  },
  template: `
    <article class="product-card">
      <img v-if="anh" class="product-image" :src="anh" :alt="ten">
      <div v-else class="product-image" role="img" :aria-label="'Chưa có ảnh cho ' + ten"></div>
      <div class="product-content">
        <h2>{{ ten }}</h2>
        <p class="product-price">{{ formatCurrency(gia) }}</p>
        <span
          class="stock-badge"
          :class="soLuongTon > 0 ? 'in-stock' : 'out-of-stock'"
        >
          {{ soLuongTon > 0 ? 'Còn hàng' : 'Hết hàng' }}
          <span>({{ soLuongTon }})</span>
        </span>
        <p v-if="moTaDangMo" class="product-description">{{ moTa }}</p>
        <div class="actions">
          <button
            type="button"
            :aria-expanded="moTaDangMo"
            @click="moTaDangMo = !moTaDangMo"
          >
            {{ moTaDangMo ? 'Ẩn mô tả' : 'Xem mô tả' }}
          </button>
          <button type="button" :disabled="soLuongTon === 0">
            {{ soLuongTon > 0 ? 'Mua' : 'Hết hàng' }}
          </button>
        </div>
      </div>
    </article>
  `
};
