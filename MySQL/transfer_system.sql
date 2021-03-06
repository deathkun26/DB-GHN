DROP DATABASE IF EXISTS TRANSFER_SYSTEM;

CREATE DATABASE TRANSFER_SYSTEM;

USE TRANSFER_SYSTEM;

SET
    NAMES utf8;

SET
    character_set_client = utf8mb4;

CREATE TABLE NGUOI_DUNG (
    maND INT NOT NULL,
    CMND CHAR(9) NOT NULL,
    hotenND VARCHAR(30) NOT NULL,
    sodienthoaiND CHAR(10) NOT NULL,
    emailND VARCHAR(40),
    Tendangnhap VARCHAR(20) NOT NULL,
    Matkhau VARCHAR(20) NOT NULL,
    PRIMARY KEY (maND),
    UNIQUE (CMND, Tendangnhap),
    CHECK (
        maND >= 10000000
        AND maND <= 99999999
    )
);

CREATE INDEX index_hoten
ON NGUOI_DUNG(hotenND);

--  chủ1
INSERT INTO
    NGUOI_DUNG
VALUES
    (
        12300000,
        '12345000',
        'Trần Văn Thái',
        '0335370078',
        'tvt@hcmut.edu.vn',
        'tvt00',
        'tvt1915121'
    );

-- nhân viên
INSERT INTO
    NGUOI_DUNG
VALUES
    (
        12300001,
        '123450001',
        'Phan Đinh Minh Toàn',
        '0335370077',
        'pdmt@hcmut.edu.vn',
        'pdmt00',
        'tvt1915122'
    );

INSERT INTO
    NGUOI_DUNG
VALUES
    (
        12300002,
        '123450002',
        'Hoàng Khánh Ly',
        '0335370076',
        'hkl@hcmut.edu.vn',
        'hkl00',
        'tvt1915123'
    );

INSERT INTO
    NGUOI_DUNG
VALUES
    (
        12300003,
        '123450003',
        'Nguyễn Hồng Dân',
        '0335370075',
        'nhd@hcmut.edu.vn',
        'nhd00',
        'tvt1915124'
    );

-- chủ 2
INSERT INTO
    NGUOI_DUNG
VALUES
    (
        23400000,
        '234560000',
        'Lê Bình Đẳng',
        '0357509011',
        'lbd@hcmut.edu.vn',
        'lbd00',
        'tvt1915121'
    );

--  nhân viên
INSERT INTO
    NGUOI_DUNG
VALUES
    (
        23400001,
        '234560001',
        'Trần Quang Huy',
        '0357509012',
        'tqh@hcmut.edu.vn',
        'tqh00',
        'tvt1915121'
    );

INSERT INTO
    NGUOI_DUNG
VALUES
    (
        23400002,
        '234560002',
        'Ngô Diễm Quỳnh',
        '0357509013',
        'ndq@hcmut.edu.vn',
        'ndq00',
        'tvt1915121'
    );

-- chủ 3
INSERT INTO
    NGUOI_DUNG
VALUES
    (
        34500000,
        '345670000',
        'Hà Duy Anh',
        '0357509015',
        'hda@hcmut.edu.vn',
        'hda00',
        'tvt1915121'
    );

--  nhân viên
INSERT INTO
    NGUOI_DUNG
VALUES
    (
        34500001,
        '345670000',
        'Nguyễn Công Thành',
        '0357509014',
        'nct@hcmut.edu.vn',
        'nct00',
        'tvt1915121'
    );

CREATE TABLE THONG_TIN_NGAN_HANG (
    maND INT NOT NULL,
    tentaikhoan VARCHAR(30) NOT NULL,
    --  ten tai khoan la ten nguoi dung viet khong dau
    sotaikhoan VARCHAR(20) NOT NULL,
    tennganhang VARCHAR(30) NOT NULL,
    PRIMARY KEY (maND, tentaikhoan, sotaikhoan, tennganhang),
    FOREIGN KEY (maND) REFERENCES NGUOI_DUNG(maND) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO
    THONG_TIN_NGAN_HANG
VALUES
    (
        12300000,
        'TRAN VAN THAI',
        '001122334466',
        'Ngân Hàng BBBank'
    );

INSERT INTO
    THONG_TIN_NGAN_HANG
VALUES
    (
        12300001,
        'PHAN DINH MINH TOAN',
        '123456123478',
        'Ngân Hàng BBBank'
    );

INSERT INTO
    THONG_TIN_NGAN_HANG
VALUES
    (
        12300002,
        'HOANG KHANH LY',
        '1357924690',
        'Ngân Hàng BBBank'
    );

INSERT INTO
    THONG_TIN_NGAN_HANG
VALUES
    (
        12300003,
        'NGUYEN HONG DAN',
        '998877665555',
        'Ngân Hàng BBBank'
    );

INSERT INTO
    THONG_TIN_NGAN_HANG
VALUES
    (
        23400000,
        'LE BINH DANG',
        '001122334433',
        'Ngân Hàng BBBank'
    );

INSERT INTO
    THONG_TIN_NGAN_HANG
VALUES
    (
        23400001,
        'TRAN QUANG HUY',
        '123456123412',
        'Ngân Hàng BBBank'
    );

INSERT INTO
    THONG_TIN_NGAN_HANG
VALUES
    (
        23400002,
        'NGO DIEM QUYNH',
        '1357924683',
        'Ngân Hàng BBBank'
    );

INSERT INTO
    THONG_TIN_NGAN_HANG
VALUES
    (
        23400000,
        'HA DUY ANH',
        '998877665545',
        'Ngân Hàng BBBank'
    );

INSERT INTO
    THONG_TIN_NGAN_HANG
VALUES
    (
        23400001,
        'NGUYEN CONG THANH',
        '998877665588',
        'Ngân Hàng BBBank'
    );

CREATE TABLE NHAN_VIEN (
    maND INT NOT NULL,
    PRIMARY KEY (maND),
    FOREIGN KEY (maND) REFERENCES NGUOI_DUNG(maND) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO
    NHAN_VIEN
VALUES
    (12300001);

INSERT INTO
    NHAN_VIEN
VALUES
    (12300002);

INSERT INTO
    NHAN_VIEN
VALUES
    (12300003);

-- -- -- -- -- -- -- -- -- -- 
INSERT INTO
    NHAN_VIEN
VALUES
    (23400001);

INSERT INTO
    NHAN_VIEN
VALUES
    (23400002);

-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
INSERT INTO
    NHAN_VIEN
VALUES
    (34500001);

CREATE TABLE CHU_CUA_HANG (
    maND INT NOT NULL,
    soluongcuahang INT NOT NULL DEFAULT 1,
    PRIMARY KEY (maND),
    FOREIGN KEY (maND) REFERENCES NGUOI_DUNG(maND) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO
    CHU_CUA_HANG
VALUES
    (12300000, DEFAULT);

INSERT INTO
    CHU_CUA_HANG
VALUES
    (23400000, DEFAULT);

INSERT INTO
    CHU_CUA_HANG
VALUES
    (34500000, DEFAULT);

CREATE TABLE CUA_HANG (
    maCH INT NOT NULL,
    maCCH INT NOT NULL,
    tenCH VARCHAR(30) NOT NULL,
    diachiCH VARCHAR(100),
    sodienthoaiCH CHAR(10),
    trangthaiCH BOOL DEFAULT FALSE,
    soluongnhanvien INT NOT NULL DEFAULT 0,
    --  FALSE la khong kich hoat, true la kich hoat. Bang hien thi duoi dang 0 / 1
    PRIMARY KEY (maCH),
    CHECK (
        maCH >= 1000000
        AND maCH <= 9999999
    ),
    FOREIGN KEY (maCCH) REFERENCES CHU_CUA_HANG(maND) ON UPDATE CASCADE ON DELETE CASCADE
);

-- CHỦ 1
INSERT INTO
    CUA_HANG
VALUES
    (
        1230001,
        12300000,
        'Cửa hàng 123 01',
        'Quận 10 - Thành phố Hồ Chí Minh',
        '0775451111',
        TRUE,
        DEFAULT
    );

INSERT INTO
    CUA_HANG
VALUES
    (
        1230002,
        12300000,
        'Cửa hàng 123 02',
        'Quận Sơn Trà - Thành phố Đà Nẵng',
        '0775451112',
        TRUE,
        DEFAULT
    );

INSERT INTO
    CUA_HANG
VALUES
    (
        1230003,
        12300000,
        'Cửa hàng 123 03',
        'Quận Tân Phú - Thành phố Hồ Chí Minh',
        '0775451113',
        FALSE,
        DEFAULT
    );

-- chủ 2
INSERT INTO
    CUA_HANG
VALUES
    (
        2340001,
        23400000,
        'Cửa hàng 234 01',
        'Huyện Dầu Tiếng - Tỉnh Bình Dương',
        '0775452221',
        TRUE,
        DEFAULT
    );

INSERT INTO
    CUA_HANG
VALUES
    (
        2340002,
        23400000,
        'Cửa hàng 234 02',
        'Thành phố Thủ Đức - Thành phố Hồ Chí Minh',
        '0775452222',
        TRUE,
        DEFAULT
    );

-- chủ 3
INSERT INTO
    CUA_HANG
VALUES
    (
        3450001,
        34500000,
        'Cửa hàng 345 01',
        'Huyện KrôngAna - Tỉnh Đắk Lắk',
        '0775453278',
        TRUE,
        DEFAULT
    );

INSERT INTO
    CUA_HANG
VALUES
    (
        3450002,
        34500000,
        'Cửa hàng 345 02',
        'Thành phố Dĩ An - Tỉnh Bình Dương',
        '0775453277',
        TRUE,
        DEFAULT
    );

CREATE TABLE LAM_VIEC_TAI (
    maCH INT NOT NULL,
    maND INT NOT NULL,
    PRIMARY KEY (maCH, maND),
    FOREIGN KEY (maCH) REFERENCES CUA_HANG(maCH) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (maND) REFERENCES NHAN_VIEN(maND) ON UPDATE CASCADE ON DELETE CASCADE
);

-- chủ 1
INSERT INTO
    LAM_VIEC_TAI
VALUES
    (1230001, 12300002);

INSERT INTO
    LAM_VIEC_TAI
VALUES
    (1230002, 12300001);

INSERT INTO
    LAM_VIEC_TAI
VALUES
    (1230002, 12300003);

INSERT INTO
    LAM_VIEC_TAI
VALUES
    (1230003, 12300001);

-- chủ 2
INSERT INTO
    LAM_VIEC_TAI
VALUES
    (2340001, 23400002);

INSERT INTO
    LAM_VIEC_TAI
VALUES
    (2340001, 23400001);

INSERT INTO
    LAM_VIEC_TAI
VALUES
    (2340002, 23400001);

INSERT INTO
    LAM_VIEC_TAI
VALUES
    (2340002, 23400002);

-- chủ 3
INSERT INTO
    LAM_VIEC_TAI
VALUES
    (3450001, 34500001);

INSERT INTO
    LAM_VIEC_TAI
VALUES
    (3450002, 34500001);

CREATE TABLE BUU_TA (
    maBT INT NOT NULL,
    CMND CHAR(9) NOT NULL,
    hotenBT VARCHAR(30) NOT NULL,
    sodienthoaiBT CHAR(10) NOT NULL,
    PRIMARY KEY (maBT),
    CHECK (
        maBT >= 1000
        AND maBT <= 9999
    )
);

INSERT INTO
    BUU_TA
VALUES
    (1230, '206368960', 'Lê Bình Đẳng', '0335370069');

INSERT INTO
    BUU_TA
VALUES
    (
        1231,
        '206368961',
        'Mai Hoàng Khải',
        '0335370068'
    );

INSERT INTO
    BUU_TA
VALUES
    (
        1232,
        '206368962',
        'Đặng Trung Kiên',
        '0335370067'
    );

INSERT INTO
    BUU_TA
VALUES
    (
        2230,
        '206368963',
        'Đặng Nguyễn Xuân Nam',
        '0335370065'
    );

INSERT INTO
    BUU_TA
VALUES
    (
        2231,
        '206368964',
        'Nguyễn Chí Trung',
        '0335370064'
    );

INSERT INTO
    BUU_TA
VALUES
    (3230, '206368965', 'Lê Đức Khoan', '0335370063');

INSERT INTO
    BUU_TA
VALUES
    (
        3231,
        '206368966',
        'Nguyễn Cảnh Hoàng',
        '0335370062'
    );

CREATE TABLE DIEM_GIAO_NHAN (
    --  dia chi cua cac buu cuc
    sttDGN INT NOT NULL,
    khuvucDGN VARCHAR(30) NOT NULL,
    diachiDGN VARCHAR(100),
    sodienthoaiDGN CHAR(10) NOT NULL,
    PRIMARY KEY (sttDGN, khuvucDGN)
);

INSERT INTO
    DIEM_GIAO_NHAN
VALUES
    (
        1,
        'Quận 10',
        'Quận 10 - Thành phố Hồ Chí Minh',
        '0775457880'
    );

INSERT INTO
    DIEM_GIAO_NHAN
VALUES
    (
        2,
        'Sơn Trà',
        'Quận Sơn Trà - Thành phố Đà Nẵng',
        '0775457880'
    );

INSERT INTO
    DIEM_GIAO_NHAN
VALUES
    (
        3,
        'Tân Phú',
        'Tân Phú - Thành phố Hồ Chí Minh',
        '0775457880'
    );

INSERT INTO
    DIEM_GIAO_NHAN
VALUES
    (
        4,
        'Dầu Tiếng',
        'Huyện Dầu Tiếng - Tỉnh Bình Dương',
        '0775457881'
    );

INSERT INTO
    DIEM_GIAO_NHAN
VALUES
    (
        5,
        'Thủ Đức',
        'Thành phố Thủ Đức - Thành phố Hồ Chí Minh',
        '0775457883'
    );

INSERT INTO
    DIEM_GIAO_NHAN
VALUES
    (
        6,
        'KrôngAna',
        'Huyện KrôngAna - Tỉnh Đắk Lắk',
        '0775457882'
    );

INSERT INTO
    DIEM_GIAO_NHAN
VALUES
    (
        7,
        'Dĩ An',
        'Thành phố Dĩ An - Tỉnh Bình Dương',
        '0775457884'
    );

CREATE TABLE DON_HANG (
    maVD INT NOT NULL,
    kichthuocDH INT NOT NULL,
    --  luu the tich
    khoiluongDH INT,
    diachitrahang VARCHAR(60),
    --  mac dinh la dia chi cua hang or others + app handle
    hotenNN VARCHAR(30),
    sodienthoaiNN CHAR(10),
    diachiNN VARCHAR(60),
    maBT INT,
    calayhang BOOL DEFAULT TRUE,
    --  true la ca sang, false la ca chieu
    maCH INT NOT NULL,
    trangthai TINYINT DEFAULT 0,
    --  trang thai nhap, đang xử lý, dang giao, hoan thanh
    ngaytaoDH DATE DEFAULT (DATE(CURRENT_TIMESTAMP)),
    --  THIEU
    PRIMARY KEY (maVD),
    FOREIGN KEY (maBT) REFERENCES BUU_TA(maBT) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (maCH) REFERENCES CUA_HANG(maCH) ON UPDATE CASCADE ON DELETE CASCADE,
    CHECK (
        maVD >= 10000000
        AND maVD <= 99999999
    ),
    CHECK (
        trangthai >= 0
        AND trangthai <= 3
    )
);

--  CHỦ 1 - CH1
INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        11111111,
        100,
        500,
        NULL,
        'Trần Quang Huy',
        '0775337890',
        "Huyện Long Thành - Tỉnh Đồng Nai",
        1230,
        1,
        1230001,
        0
    );

INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        11111112,
        150,
        NULL,
        NULL,
        'Hà Duy Anh',
        '0772463000',
        "Huyện KrôngAna - Tỉnh Đắk Lắk",
        1230,
        1,
        1230001,
        2
    );

INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        11111113,
        30,
        NULL,
        NULL,
        'Lê Bình Đẳng',
        '0352123456',
        "Quận Tân Phú - Thành phố Hồ Chí Minh",
        1230,
        1,
        1230001,
        1
    );

INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        11111114,
        200,
        NULL,
        NULL,
        'Nguyễn Công Thành',
        '0352123210',
        "Quận Thủ Đức - Thành phố Hồ Chí Minh",
        1230,
        0,
        1230001,
        3
    );

INSERT INTO
    DON_HANG (maVD, kichthuocDH, khoiluongDH, maCH)
VALUES
    (11111115, 300, NULL, 1230001);

--  CH2
INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        12222221,
        100,
        NULL,
        NULL,
        'Trần Quang Huy',
        '0775337890',
        "Huyện Long Thành - Tỉnh Đồng Nai",
        1231,
        1,
        1230002,
        0
    );

INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        12222222,
        150,
        NULL,
        NULL,
        'Hà Duy Anh',
        '0772463000',
        "Huyện KrôngAna - Tỉnh Đắk Lắk",
        1231,
        0,
        1230002,
        2
    );

INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        12222223,
        30,
        NULL,
        NULL,
        'Hoàng Khánh Ly',
        '0357500980',
        "Huyện Yên Thành - Tỉnh Nghệ An",
        1231,
        0,
        1230002,
        1
    );

 --  CH3
 DELETE FROM DON_HANG WHERE maVD = 13333331;
 INSERT INTO DON_HANG
 VALUES (13333331, 100, 0, NULL, 'Nguyễn Lan Hương', '0775337654', "Quận Bình Thạnh - Thành phố Hồ Chí Minh", 1232, 1, 1230003, 2, NULL);
-- CHỦ 2 - CH1
INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        21111111,
        112,
        NULL,
        NULL,
        'Ngô Diễm Quỳnh',
        '0357896098',
        "Huyện Buôn Hồ - Tỉnh Đắk Lắk",
        2230,
        0,
        2340001,
        1
    );

-- CH2
INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        22222221,
        30,
        NULL,
        NULL,
        'Hoàng Khánh Ly',
        '0357500980',
        "Huyện Yên Thành - Tỉnh Nghệ An",
        2231,
        0,
        2340002,
        0
    );

INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        22222222,
        50,
        NULL,
        NULL,
        'Lê Thanh Sang',
        '0357896086',
        "Quận Thủ Đức - Thành phố Hồ Chí Minh",
        2231,
        0,
        2340002,
        3
    );

-- CHỦ 3 - CH1
INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        31111111,
        30,
        NULL,
        NULL,
        'Lê Đức Khoan',
        '0357508876',
        "Thành phố Dĩ An - Tỉnh Bình Dương",
        3230,
        1,
        3450001,
        2
    );

INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        31111112,
        30,
        NULL,
        NULL,
        'Phan Đinh Minh Toàn',
        '0357508876',
        "Thành phố Pleiku - Tỉnh Gia Lai",
        3230,
        1,
        3450001,
        0
    );

-- CH2
INSERT INTO
    DON_HANG(maVD, kichthuocDH,khoiluongDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, maBT, calayhang, maCH, trangthai)
VALUES
    (
        32222221,
        130,
        NULL,
        NULL,
        'Trần Văn Thái',
        '0357508876',
        "Quận Sơn Trà - Thành phố Đà Nẵng",
        3231,
        1,
        3450002,
        3
    );

CREATE TABLE SAN_PHAM (
    maVD INT NOT NULL,
    sttSP INT NOT NULL,
    tenSP VARCHAR(30),
    soluongSP INT,
    khoiluongSP INT,
    --  don vi gam
    PRIMARY KEY (maVD, sttSP),
    FOREIGN KEY (maVD) REFERENCES DON_HANG(maVD) ON UPDATE CASCADE ON DELETE CASCADE
);

-- CHUR1-CH1-DH1
INSERT INTO
    SAN_PHAM
VALUES
    (11111111, 1, 'Bút 1', 5, 5);

INSERT INTO
    SAN_PHAM
VALUES
    (11111111, 2, 'Bút 2', 5, 5);

INSERT INTO
    SAN_PHAM
VALUES
    (11111111, 3, 'Sách 1', 2, 10);

--  CH1-DH2
INSERT INTO
    SAN_PHAM
VALUES
    (11111112, 1, 'Sách 1', 2, 10);

INSERT INTO
    SAN_PHAM
VALUES
    (11111112, 2, 'Thước 1', 1, 3);

--  CH1-DH3
INSERT INTO
    SAN_PHAM
VALUES
    (11111113, 1, 'Bút 2', 2, 5);

INSERT INTO
    SAN_PHAM
VALUES
    (11111113, 2, 'Bút 3', 5, 5);

INSERT INTO
    SAN_PHAM
VALUES
    (11111113, 3, 'Sách 2', 2, 10);

INSERT INTO
    SAN_PHAM
VALUES
    (11111113, 4, 'Thước 1', 1, 3);

--  CH1-DH4
INSERT INTO
    SAN_PHAM
VALUES
    (11111114, 1, 'Bút 2', 2, 5);

INSERT INTO
    SAN_PHAM
VALUES
    (11111114, 2, 'Bút 3', 5, 5);

INSERT INTO
    SAN_PHAM
VALUES
    (11111114, 3, 'Sách 2', 2, 10);

--  CH2-DH1
INSERT INTO
    SAN_PHAM
VALUES
    (12222221, 1, 'Sách 1', 1, 10);

INSERT INTO
    SAN_PHAM
VALUES
    (12222221, 2, 'Thước 1', 2, 5);

--  CH2-DH2
INSERT INTO
    SAN_PHAM
VALUES
    (12222222, 1, 'Bút 2', 10, 50);

--  CH2-DH3
INSERT INTO
    SAN_PHAM
VALUES
    (12222223, 1, 'Sách 1', 1, 20);

INSERT INTO
    SAN_PHAM
VALUES
    (12222223, 2, 'Thước 2', 4, 20);

INSERT INTO
    SAN_PHAM
VALUES
    (12222223, 3, 'Sách 3', 2, 50);

--  --  CH3-DH1
--  INSERT INTO SAN_PHAM
--  VALUES (13333331, 1, 'Sách 1', 20, 100);
--  INSERT INTO SAN_PHAM
--  VALUES (13333331, 2, 'Bút 1', 5, 100);
--  INSERT INTO SAN_PHAM
--  VALUES (13333331, 3, 'Sách 2', 20, 100);
-- CHỦ 2-CH1-DH1
INSERT INTO
    SAN_PHAM
VALUES
    (21111111, 1, 'Giày 2', 1, 100);

--  CH2-DH1
INSERT INTO
    SAN_PHAM
VALUES
    (22222221, 1, 'Sandal 2', 2, 100);

INSERT INTO
    SAN_PHAM
VALUES
    (22222221, 2, 'Giày 2', 1, 100);

-- CH2-DH2
INSERT INTO
    SAN_PHAM
VALUES
    (22222222, 1, 'Giày 3', 1, 100);

INSERT INTO
    SAN_PHAM
VALUES
    (22222222, 2, 'Sandal 3', 1, 100);

--  CHỦ 3-CH1-DH1
INSERT INTO
    SAN_PHAM
VALUES
    (31111111, 1, 'Áo 3', 1, 50);

INSERT INTO
    SAN_PHAM
VALUES
    (31111111, 2, 'Quần 1', 1, 100);

-- CH1-DH2
INSERT INTO
    SAN_PHAM
VALUES
    (31111112, 1, 'Quần 2', 2, 200);

--  CH2-DH1
INSERT INTO
    SAN_PHAM
VALUES
    (32222221, 1, 'Áo 2', 10, 500);

INSERT INTO
    SAN_PHAM
VALUES
    (32222221, 2, 'Quần 1', 5, 500);

CREATE TABLE GUI_TOI (
    maVD INT NOT NULL,
    sttDGN INT NOT NULL,
    khuvucDGN VARCHAR(30) NOT NULL,
    PRIMARY KEY (maVD),
    FOREIGN KEY (maVD) REFERENCES DON_HANG(maVD) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (sttDGN, khuvucDGN) REFERENCES DIEM_GIAO_NHAN(sttDGN, khuvucDGN) ON UPDATE CASCADE ON DELETE CASCADE
);

--  CHỦ 1
-- CH1-DH1
INSERT INTO
    GUI_TOI
VALUES
    (11111111, 1, 'Quận 10');

--  CH1-DH2
INSERT INTO
    GUI_TOI
VALUES
    (11111112, 1, 'Quận 10');

--  CH1-DH3
INSERT INTO
    GUI_TOI
VALUES
    (11111113, 1, 'Quận 10');

--  CH1-DH4
INSERT INTO
    GUI_TOI
VALUES
    (11111114, 1, 'Quận 10');

--  CH2-DH1
INSERT INTO
    GUI_TOI
VALUES
    (12222221, 2, 'Sơn Trà');

--  CH2-DH2
INSERT INTO
    GUI_TOI
VALUES
    (12222222, 2, 'Sơn Trà');

--  CH2-DH3
INSERT INTO
    GUI_TOI
VALUES
    (12222223, 2, 'Sơn Trà');

--  --  CH3-DH1
--  INSERT INTO GUI_TOI
--  VALUES (13333331, 3, 'Tân Phú');
-- CHỦ 2
--  CH1-DH1
INSERT INTO
    GUI_TOI
VALUES
    (21111111, 4, 'Dầu Tiếng');

--  CH2-DH1
INSERT INTO
    GUI_TOI
VALUES
    (22222221, 5, 'Thủ Đức');

--  CH2-DH2
INSERT INTO
    GUI_TOI
VALUES
    (22222222, 5, 'Thủ Đức');

-- CHỦ 3
--  CH1-DH1
INSERT INTO
    GUI_TOI
VALUES
    (31111111, 6, 'KrôngAna');

--  CH1-DH1
INSERT INTO
    GUI_TOI
VALUES
    (31111112, 6, 'KrôngAna');

--  CH2-DH1
INSERT INTO
    GUI_TOI
VALUES
    (32222221, 7, 'Dĩ An');

CREATE TABLE TRA_HANG (
    maVD INT NOT NULL,
    sttDGN INT NOT NULL,
    khuvucDGN VARCHAR(30) NOT NULL,
    PRIMARY KEY (maVD),
    FOREIGN KEY (maVD) REFERENCES DON_HANG(maVD) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (sttDGN, khuvucDGN) REFERENCES DIEM_GIAO_NHAN(sttDGN, khuvucDGN) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO
    TRA_HANG
VALUES
    (11111113, 1, 'Quận 10');

INSERT INTO
    TRA_HANG
VALUES
    (22222222, 5, 'Thủ Đức');

CREATE TABLE YEU_CAU_HO_TRO (
    maYC INT NOT NULL,
    loaiYC TINYINT NOT NULL,
    noidungYC VARCHAR(1000),
    trangthaiYC TINYINT NOT NULL DEFAULT 0,
    --  0: da gui / 1: dang xu ly / 2: hoan thanh
    PRIMARY KEY(maYC),
    CHECK (
        maYC >= 10000000
        AND maYC <= 99999999
    ),
    CHECK (
        loaiYC >= 0
        AND loaiYC <= 3
    ),
    CHECK (
        trangthaiYC >= 0
        AND trangthaiYC <= 2
    )
);

INSERT INTO
    YEU_CAU_HO_TRO
VALUES
    (99000000, 0, 'Yêu cầu hỗ trợ', 2);

INSERT INTO
    YEU_CAU_HO_TRO
VALUES
    (99000001, 1, 'Yêu cầu hỗ trợ 1', 1);

INSERT INTO
    YEU_CAU_HO_TRO
VALUES
    (99000002, 2, 'Yêu cầu hỗ trợ 2', 1);

INSERT INTO
    YEU_CAU_HO_TRO
VALUES
    (99000003, 3, 'Yêu cầu hỗ trợ 3', 0);

INSERT INTO
    YEU_CAU_HO_TRO
VALUES
    (99000004, 2, 'Yêu cầu hỗ trợ 4', 2);

INSERT INTO
    YEU_CAU_HO_TRO
VALUES
    (99000005, 1, 'Yêu cầu hỗ trợ 5', 0);

INSERT INTO
    YEU_CAU_HO_TRO
VALUES
    (99000006, 3, 'Yêu cầu hỗ trợ 6', 1);
 
INSERT INTO
    YEU_CAU_HO_TRO
VALUES
    (99000007, 3, 'Yêu cầu hỗ trợ 7', 2);

CREATE TABLE GUI_YEU_CAU (
    maND INT NOT NULL,
    maYC INT NOT NULL,
    maVD INT NOT NULL,
    thoigiangui DATE DEFAULT (DATE(CURRENT_TIMESTAMP)),
    PRIMARY KEY (maND, maYC),
    FOREIGN KEY (maND) REFERENCES NGUOI_DUNG(maND) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (maYC) REFERENCES YEU_CAU_HO_TRO(maYC) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (maVD) REFERENCES DON_HANG(maVD) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO
    GUI_YEU_CAU(maND, maYC, maVD)
VALUES
    (12300000, 99000000, 12222221);

INSERT INTO
    GUI_YEU_CAU(maND, maYC, maVD)
VALUES
    (12300000, 99000001, 12222221);

INSERT INTO
    GUI_YEU_CAU(maND, maYC, maVD)
VALUES
    (12300000, 99000002, 12222221);

INSERT INTO
    GUI_YEU_CAU(maND, maYC, maVD)
VALUES
    (23400001, 99000003, 21111111);

INSERT INTO
    GUI_YEU_CAU(maND, maYC, maVD)
VALUES
    (34500001, 99000004, 31111111);

INSERT INTO
    GUI_YEU_CAU(maND, maYC, maVD)
VALUES
    (34500000, 99000005, 32222221);

INSERT INTO
    GUI_YEU_CAU(maND, maYC, maVD)
VALUES
    (23400000, 99000006, 22222221);

INSERT INTO
    GUI_YEU_CAU(maND, maYC, maVD)
VALUES
    (12300001, 99000007, 12222221);
--  CREATE TABLE NHOM_QUYEN (
--      maCCH CHAR(15) NOT NULL,
--      tenNQ VARCHAR(30) NOT NULL,
--      motaNQ VARCHAR(1000),
--      PRIMARY KEY (maCCH, tenNQ),
--      FOREIGN KEY (maCCH) REFERENCES CHU_CUA_HANG(maND)
--      ON UPDATE CASCADE
--      ON DELETE CASCADE
--  );
--  INSERT INTO NHOM_QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Quyền cao nhất của người dùng');
--  INSERT INTO NHOM_QUYEN
--  VALUES ('000000000000004', 'Nhân viên', 'Quyền mặc định của nhân viên thuộc 1 cửa hàng');
--  CREATE TABLE QUYEN (
--      maCCH CHAR(15) NOT NULL,
--      tenNQ VARCHAR(30) NOT NULL,
--      Quyen VARCHAR(500),
--      PRIMARY KEY (maCCH, tenNQ),
--      FOREIGN KEY (maCCH) REFERENCES NHOM_QUYEN(maCCH)
--      ON UPDATE CASCADE
--      ON DELETE CASCADE,
--      FOREIGN KEY (tenNQ) REFERENCES NHOM_QUYEN(tenNQ)
--      ON UPDATE CASCADE
--      ON DELETE CASCADE
--  );
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Cập nhật cửa hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Thêm nhân viên vào cửa hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Thêm người dùng vào nhóm quyền');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Xem cửa hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Xem danh sách nhân viên của cửa hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Xoá nhân viên khỏi cửa hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Chỉnh sửa đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Tạo đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Huỷ đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Xem chi tiết đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Xem danh sách đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Yêu cầu giao lại đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Yêu cầu trả hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Cập nhật yêu cầu hỗ trợ');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Tạo yêu cầu hỗ trợ');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Xem chi tiết yêu cầu.hỗ trợ');
--  INSERT INTO QUYEN
--  VALUES ('000000000000003', 'Chủ cửa hàng', 'Xem danh sách yêu cầu hỗ trợ');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Chỉnh sửa đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Tạo đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Huỷ đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Xem chi tiết đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Xem danh sách đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Yêu cầu giao lại đơn hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Yêu cầu trả hàng');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Cập nhật yêu cầu hỗ trợ');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Tạo yêu cầu hỗ trợ');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Xem chi tiết yêu cầu.hỗ trợ');
--  INSERT INTO QUYEN
--  VALUES ('000000000000002', 'Nhân viên', 'Xem danh sách yêu cầu hỗ trợ');
--  CREATE TABLE PHAN_QUYEN (
--      maCH CHAR(20) NOT NULL,
--      maNV CHAR(15) NOT NULL,
--      maCCH CHAR(15) NOT NULL,
--      tenNQ VARCHAR(30) NOT NULL,
--      PRIMARY KEY (maCH, maNV),
--      FOREIGN KEY (maCH) REFERENCES CUA_HANG(maCH),
--      FOREIGN KEY (maNV) REFERENCES NHAN_VIEN(maND),
--      FOREIGN KEY (maCCH) REFERENCES CHU_CUA_HANG(maND),
--      FOREIGN KEY (tenNQ) REFERENCES NHOM_QUYEN(tenNQ) 
--  ); 
--  INSERT INTO PHAN_QUYEN
--  VALUES ('00000000000000000001', '000000000000002', '000000000000003', 'Nhân viên');
--  chủ cửa hàng - chủ cửa hàng
--  CREATE TABLE KHUYEN_MAI (
--      maKM CHAR(8) NOT NULL,
--      motaKM VARCHAR(500),
--      luongtiengiam FLOAT(1),
--      giatoithieu INT,
--      giamtoida INT,
--      PRIMARY KEY (maKM),
--      CHECK (luongtiengiam > 0 AND luongtiengiam <= 1)
--  );
--  INSERT INTO KHUYEN_MAI
--  VALUES ('HA000001', 'Giảm 15% cho khách hàng mới có đơn hàng trị giá hơn 100.000 VNĐ, tối đa 50.000 VNĐ', 0.15, 100000, 50000);
--  INSERT INTO KHUYEN_MAI
--  VALUES ('ME000001', 'Giảm 20% cho mọi đơn hàng trị giá hơn 100.000 VNĐ, tối đa 50.000 VNĐ', 0.2, 100000, 50000);

-- DELIMITER //

-- CREATE PROCEDURE capNhatDonHang(
-- 		IN ma_van_don INT,
--         IN dia_chi_tra_ve VARCHAR(60),
--         IN ca_lay BOOL,
--         IN kich_thuoc INT,
--         IN trang_thai TINYINT,
-- 		IN ho_ten VARCHAR(30),
-- 		IN so_dien_thoai CHAR(10),
-- 		IN dia_chi VARCHAR(60),
--         IN DGN VARCHAR(60)
-- )
-- BEGIN
-- 	DECLARE stt_DGN INT;
--     DECLARE khu_vuc_DGN VARCHAR(30);
-- 	SET stt_DGN = CONVERT(SUBSTRING_INDEX(DGN,' -',1),UNSIGNED INTEGER); -- test
--     SET khu_vuc_DGN = SUBSTRING_INDEX(DGN, "- ", -1);
-- 	UPDATE DON_HANG
--     SET diachitrahang = dia_chi_tra_ve, calayhang = ca_lay, kichthuocDH = kich_thuoc, trangthai = trang_thai, hotenNN = ho_ten, sodienthoaiNN = so_dien_thoai, diachiNN = dia_chi, ngaytaoDH = DEFAULT
--     WHERE maVD = ma_van_don;
--     IF EXISTS(SELECT * FROM GUI_TOI WHERE maVD = ma_van_don) THEN
-- 		IF DGN = '' THEN
-- 			DELETE FROM GUI_TOI WHERE maVD = ma_van_don;
--         ELSE
-- 			UPDATE GUI_TOI
-- 			SET sttDGN = stt_DGN, khuvucDGN = khu_vuc_DGN
-- 			WHERE maVD = ma_van_don;
--         END IF;
--     ELSE
-- 		IF DGN <> '' THEN
-- 			INSERT INTO GUI_TOI
--             VALUES (ma_van_don, stt_DGN, khu_vuc_DGN);
-- 		END IF;
--     END IF;
--     DELETE FROM SAN_PHAM WHERE maVD = ma_van_don;
-- END //

-- DELIMITER ;

-- DELIMITER //

-- CREATE TRIGGER capNhatThoiGianYC
--     AFTER UPDATE
--     ON YEU_CAU_HO_TRO FOR EACH ROW
-- BEGIN
-- 	UPDATE GUI_YEU_CAU
-- 	SET thoigiangui = DATE(CURRENT_TIMESTAMP)
-- 	WHERE GUI_YEU_CAU.maYC = OLD.maYC;
-- END//  

-- DELIMITER ;
