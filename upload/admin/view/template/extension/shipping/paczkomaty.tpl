<?php echo  $header; ?>
<?php echo  $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-shipping" data-toggle="tooltip" title="<?php echo  $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo  $cancel; ?>" data-toggle="tooltip" title="<?php echo  $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo  $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb):?>
                    <li><a href="<?php echo  $breadcrumb['href']; ?>"><?php echo  $breadcrumb['text']; ?></a></li>
                     <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
         <?php  if ($error_warning):?>
            <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo  $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
         <?php  endif; ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo  $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo  $action; ?>" method="post" enctype="multipart/form-data" id="form-shipping" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cost">Uaktualnij liste paczkomatów</label>
                        <div class="col-sm-10">
                            <button  id="refresh" class="btn btn-primary" type="button">aktualizuj</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cost"><?php echo  $entry_cost; ?> rozmiar 8x38x64cm</label>
                        <div class="col-sm-10">
                            <input type="text" name="paczkomaty_cost" value="<?php echo  $shipping_paczkomaty_cost; ?>" placeholder="<?php echo  $entry_cost; ?>" id="input-cost" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cost1"><?php echo  $entry_cost; ?>rozmiar 19x38x64cm</label>
                        <div class="col-sm-10">
                            <input type="text" name="paczkomaty_cost1" value="<?php echo  $shipping_paczkomaty_cost1; ?>" placeholder="<?php echo  $entry_cost; ?>" id="input-cost1" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cost2"><?php echo  $entry_cost; ?>rozmiar 41x38x64cm</label>
                        <div class="col-sm-10">
                            <input type="text" name="paczkomaty_cost2" value="<?php echo  $shipping_paczkomaty_cost2; ?>" placeholder="<?php echo  $entry_cost; ?>" id="input-cost2" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-tax-class"><?php echo  $entry_tax_class; ?></label>
                        <div class="col-sm-10">
                            <select name="paczkomaty_tax_class_id" id="input-tax-class" class="form-control">
                                <option value="0"><?php echo  $text_none; ?></option>
                                 <?php foreach ($tax_classes as $tax_class):?>
                                    <?php if ($tax_class['tax_class_id'] == $shipping_paczkomaty_tax_class_id):?>
                                        <option value="<?php echo  $tax_class['tax_class_id']; ?>" selected="selected"><?php echo  $tax_class['title']; ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo  $tax_class['tax_class_id']; ?>"><?php echo  $tax_class['title']; ?></option>
                                     <?php  endif; ?>
                                 <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo  $entry_geo_zone; ?></label>
                        <div class="col-sm-10">
                            <select name="paczkomaty_geo_zone_id" id="input-geo-zone" class="form-control">
                                <option value="0"><?php echo  $text_all_zones; ?></option>
                                 <?php foreach ($geo_zones as $geo_zone): ?>
                                     <?php  if ($geo_zone['geo_zone_id'] == $shipping_paczkomaty_geo_zone_id):?>
                                        <option value="<?php echo  $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo  $geo_zone['name']; ?></option>
                                     <?php  else :?>
                                        <option value="<?php echo  $geo_zone['geo_zone_id']; ?>"><?php echo  $geo_zone['name']; ?></option>
                                     <?php  endif; ?>
                                 <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo  $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="paczkomaty_status" id="input-status" class="form-control">
                                 <?php  if ($paczkomaty_status):?>
                                    <option value="1" selected="selected"><?php echo  $text_enabled; ?></option>
                                    <option value="0"><?php echo  $text_disabled; ?></option>
                                 <?php  else :?>
                                    <option value="1"><?php echo  $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo  $text_disabled; ?></option>
                                 <?php  endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo  $entry_sort_order; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="paczkomaty_sort_order" value="<?php echo  $shipping_paczkomaty_sort_order; ?>" placeholder="<?php echo  $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-sort-order">API KEY(maps google)</label>
                        <div class="col-sm-10">
                        <input type="text" name="paczkomaty_apikey" value="<?php echo  $shipping_paczkomaty_apikey; ?>" placeholder="klucz do google maps api" id="input-sort-order" class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
	$('#refresh').click(function () {
            $.ajax({
                url: '/admin/index.php?route=extension/shipping/paczkomaty/refresh&token=<?php echo $token; ?>',
                dataType: 'html',
                beforeSend: function () {
                    $('#refresh').button('loading');
                },
                success: function (html) {
                    $('#refresh').button('reset');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });



</script>
<?php echo  $footer; ?>