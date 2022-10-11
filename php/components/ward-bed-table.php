           
         
           <div class="ward-bed-top-details">
                    <h3>Head Doctor Name - <?php echo "$HeadDoc" ?></h3>
                    <h3>Ward  - <?php echo "$wardNo" ?></h3>
                    <h3>Total Beds  -  <?php echo "$total_bed_count" ?></h3>
                    <h3>Available Beds  -  <?php echo $total_bed_count - $filled_bed_count ?></h3>
                    <h3>Filled Beds  - <?php echo "$filled_bed_count " ?></h3>
            </div>
                <hr/>

            <div class="ward-bed-container">
   
                <div class="viewpage-top-container">
                             
                </div>

                <table class="detail-table">
                        <tr>
                            <th id="id-col">Bed No</th>
                            <th>Patient's Name</th>
                            <?php 
                                  if($_SESSION['access'] == 'admin'){
                                    echo '<th id="action-col">Action</th>';
                                  }
                            ?>
                        </tr>
                        
                        <?php echo $bed_list; ?>
                </table>
            
            </div>