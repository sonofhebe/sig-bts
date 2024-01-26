let itemset1Component = `<div class="panel-heading">
                            <h3 class="panel-title">1 Itemset</h3>
                            </div>
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Transaksi</th>
                                                <th>Support</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemset1-body">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>`

let itemset2Component = `<div class="panel-heading">
                            <h3 class="panel-title">2 Itemset</h3>
                            </div>
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Transaksi</th>
                                                <th>Support</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemset2-body">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>`

let itemset3Component = `<div class="panel-heading">
                            <h3 class="panel-title">3 Itemset</h3>
                            </div>
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Transaksi</th>
                                                <th>Support</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemset3-body">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>`

let itemset4Component = `<div class="panel-heading">
                            <h3 class="panel-title">4 Itemset</h3>
                            </div>
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Transaksi</th>
                                                <th>Support</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemset4-body">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>`

let rule2Component = `<div class="panel-heading">
                            <h3 class="panel-title">Aturan 2 Itemset</h3>
                            </div>
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Rule</th>
                                                <th>&Sigma; A&B</th>
                                                <th>&Sigma; A</th>
                                                <th>Confidence</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rule2-body">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>`

let rule3Component = `<div class="panel-heading">
                            <h3 class="panel-title">Aturan 3 Itemset</h3>
                            </div>
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Rule</th>
                                                <th>&Sigma; A&B</th>
                                                <th>&Sigma; A</th>
                                                <th>Confidence</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rule3-body">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>`

let rule4Component = `<div class="panel-heading">
                            <h3 class="panel-title">Aturan 4 Itemset</h3>
                            </div>
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Rule</th>
                                                <th>&Sigma; A&B</th>
                                                <th>&Sigma; A</th>
                                                <th>Confidence</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rule4-body">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>`

function finalComponent() {
    return `<div class="panel-heading">
                <h3 class="panel-title">Asosiasi Final</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12" id="asosiasi-final">
                        <p>Aturan yang terpilih adalah yang memiliki nilai confidence &ge; ${$('#min-confidence').val()}</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Rule</th>
                                    <th>Support</th>
                                    <th>Confidence</th>
                                    <th>Sup x Con</th>
                                </tr>
                            </thead>
                            <tbody id="final-rule">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr>

            <div class="panel-heading">
                <h3 class="panel-title">Hasil</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12" id="final-result">
                        <p><i class="fas fa-chevron-right"></i> &nbsp; Lorem ipsum dolor sit amet.</p>
                    </div>
                </div>
            </div>`
}