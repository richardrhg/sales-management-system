<div class="row">
    <div class="col-12">
        <h2><i class="bi bi-speedometer2"></i> 儀表板</h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">員工管理</h4>
                        <p class="card-text">管理公司員工資料</p>
                    </div>
                    <div>
                        <i class="bi bi-people" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <a href="index.php?page=employees" class="btn btn-light mt-2">
                    <i class="bi bi-arrow-right"></i> 前往管理
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">產品管理</h4>
                        <p class="card-text">管理公司產品資料</p>
                    </div>
                    <div>
                        <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <a href="index.php?page=products" class="btn btn-light mt-2">
                    <i class="bi bi-arrow-right"></i> 前往管理
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">銷售管理</h4>
                        <p class="card-text">管理銷售紀錄</p>
                    </div>
                    <div>
                        <i class="bi bi-cart-check" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <a href="index.php?page=sales" class="btn btn-light mt-2">
                    <i class="bi bi-arrow-right"></i> 前往管理
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h5><i class="bi bi-star"></i> 必推商品</h5>
            </div>
            <div class="card-body">
                <div id="recommendedProducts">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">載入中...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5><i class="bi bi-trophy"></i> 銷售排行榜</h5>
            </div>
            <div class="card-body">
                <div id="salesRanking">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">載入中...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

