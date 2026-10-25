<form action="processData_form.php" method="POST">
    <fieldset>
        <legend>Data Diri</legend>

        <p>
            <label for="nama">Nama:</label><br>
            <input type="text" id="nama" name="nama" 
                   value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">
        </p>

        <p>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </p>

        <p>
            <label for="kata_sandi">Kata Sandi:</label><br>
            <input type="password" id="kata_sandi" name="kata_sandi" 
                   value="<?php echo isset($_POST['kata_sandi']) ? htmlspecialchars($_POST['kata_sandi']) : ''; ?>">
        </p>

        <p>
            <label for="detail_alamat">Detail Alamat:</label><br>
            <textarea id="detail_alamat" name="detail_alamat" rows="3" cols="30"><?php echo isset($_POST['detail_alamat']) ? htmlspecialchars($_POST['detail_alamat']) : ''; ?></textarea>
        </p>

        <p>
            <label for="kota">Kota:</label><br>
            <select id="kota" name="kota">
                <option value="">-- Pilih Kotamu --</option>
                <?php
                $daftar_kota = array("Jakarta", "Bogor", "Depok", "Tangerang", "Bekasi");
                foreach ($daftar_kota as $k) {
                    $selected = (isset($_POST['kota']) && $_POST['kota'] == $k) ? 'selected' : '';
                    echo "<option value='$k' $selected>$k</option>";
                }
                ?>
            </select>
        </p>

        <input type="hidden" name="negara" value="Indonesia">

        <p>
            <label>Jenis Kelamin:</label><br>
            <input type="radio" id="pria" name="jenis_kelamin" value="Pria"
                   <?php echo (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Pria') ? 'checked' : ''; ?>>
            <label for="pria">Pria</label>
            
            <input type="radio" id="wanita" name="jenis_kelamin" value="Wanita"
                   <?php echo (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Wanita') ? 'checked' : ''; ?>>
            <label for="wanita">Wanita</label>
        </p>

        <p>
            <label for="vegetarian">Vegetarian?</label>
            <input type="checkbox" id="vegetarian" name="vegetarian" value="Ya"
                   <?php echo (isset($_POST['vegetarian']) && $_POST['vegetarian'] == 'Ya') ? 'checked' : ''; ?>>
        </p>

        <p>
            <input type="submit" name="kirim" value="Kirim">
            <input type="reset" name="reset" value="Riset">
        </p>

    </fieldset>
</form>