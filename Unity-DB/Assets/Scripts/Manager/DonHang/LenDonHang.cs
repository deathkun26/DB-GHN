using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;
using System;

public enum PhamVi
{
    CungTinh, KhacTinh
}
public class LenDonHang : MonoBehaviour
{
    public static LenDonHang instance;

    #region Bên Gửi
    public Text ten_SdtGui;
    public Text diaChiGui;
    public Toggle coDiaChiTraHang;
    public Toggle coGuiTaiDiemGiaoNhan;
    public Dropdown caLayHang;
    public InputField diaChiTraHang;
    public Dropdown tinhTra;
    [Space(5)]
    [Header("Điểm giao nhận")]
    public GameObject diemGiaoNhan;
    public List<string> danhSachDGN;
    public Transform containerDGN;
    public GameObject diemGiaoNhanPrefab;
    public Text tenDGN;
    public Text diachiDGN;
    #endregion

    [Space(10)]

    #region Bên Nhận
    public InputField sdtNhan;
    public InputField hoTenNhan;
    public InputField diaChiNhan;
    public Dropdown tinhNhan;
    #endregion

    [Space(10)]

    #region Sản Phẩm
    public Transform container;
    public GameObject sanPhamPrefab;
    private int count;
    #endregion

    [Space(10)]

    #region Gói Hàng
    public Text tongKL;
    public Text tongGiaText;
    public GameObject luuDonNhapBtn;
    public GameObject taoDonNhapBtn;

    #endregion

    [Space(10)]

    #region Dữ Liệu
    int tongKhoiLuong;
    int tongGia;
    int kichThuoc;
    PhamVi phamVi;
    bool isWaiting = true;
    #endregion

    private void Awake()
    {
        if (instance == null)
            instance = this;
    }


    // Todo: Tạo 1 đơn mới
    public void Load()
    {
        gameObject.SetActive(true);

        count = 0;

        StartCoroutine(LayThongTinCuaHangHandler());

        tinhNhan.ClearOptions();
        tinhTra.ClearOptions();

        foreach (var tinh in Manager.instance.danhSachTinh)
        {
            tinhNhan.options.Add(new Dropdown.OptionData(tinh.name));
            tinhTra.options.Add(new Dropdown.OptionData(tinh.name));
        }

        Manager.ClearContainer(container, clearHeader: true);

        GameObject sanPhamObj = Instantiate(sanPhamPrefab, container);
        sanPhamObj.GetComponentsInChildren<Text>()[0].text = (++count).ToString();

        Manager.ClearContainer(containerDGN, clearHeader: true);
        StartCoroutine(LayDanhSachDGN());

        caLayHang.ClearOptions();
        string date = DateTime.Now.AddDays(1).ToString().Split(' ')[0];
        caLayHang.options.Add(new Dropdown.OptionData("7:00 - 12:00 " + date));
        caLayHang.options.Add(new Dropdown.OptionData("12:00 - 18:00 " + date));

        sdtNhan.text = "";
        hoTenNhan.text = "";
        diaChiNhan.text = "";
        coDiaChiTraHang.isOn = false;
        coGuiTaiDiemGiaoNhan.isOn = false;

        luuDonNhapBtn.SetActive(false);
        taoDonNhapBtn.SetActive(true);
    }

    // Todo: Load dữ liệu của đơn nháp
    public void Load(string maVanDon)
    {
        gameObject.SetActive(true);

        count = 0;

        StartCoroutine(LayThongTinCuaHangHandler());

        tinhNhan.ClearOptions();
        tinhTra.ClearOptions();

        foreach (var tinh in Manager.instance.danhSachTinh)
        {
            tinhNhan.options.Add(new Dropdown.OptionData(tinh.name));
            tinhTra.options.Add(new Dropdown.OptionData(tinh.name));
        }

        Manager.ClearContainer(container, clearHeader: true);

        Manager.ClearContainer(containerDGN, clearHeader: true);
        isWaiting = true;
        StartCoroutine(LayDanhSachDGN());

        caLayHang.ClearOptions();
        string date = DateTime.Now.AddDays(1).ToString().Split(' ')[0];
        caLayHang.options.Add(new Dropdown.OptionData("7:00 - 12:00 " + date));
        caLayHang.options.Add(new Dropdown.OptionData("12:00 - 18:00 " + date));

        StartCoroutine(LayDonHangHandler(maVanDon));

        taoDonNhapBtn.SetActive(false);
        luuDonNhapBtn.SetActive(true);

    }

    public void Exit()
    {

        gameObject.SetActive(false);
    }

    public void ActiveDiaChiTraHang()
    {
        diaChiTraHang.gameObject.SetActive(coDiaChiTraHang.isOn);
        tinhTra.gameObject.SetActive(coDiaChiTraHang.isOn);
    }

    public void ActiveDiemGiaoNhan()
    {
        diemGiaoNhan.gameObject.SetActive(coGuiTaiDiemGiaoNhan.isOn);
    }

    public void CheckPhamVi()
    {
        string tinhGui = diaChiGui.text.Split('-')[1];
        if (tinhGui[0] == ' ')
            tinhGui = tinhGui.Remove(0, 1);

        if (diachiDGN.text != "" && coGuiTaiDiemGiaoNhan.isOn)
        {
            //Debug.Log(diachiDGN.text);
            tinhGui = diachiDGN.text.Split('-')[1].Remove(0, 1);
        }
        else
            tinhGui = "";
        //Debug.Log(tinhGui);

        if (tinhGui == tinhNhan.captionText.text)
            phamVi = PhamVi.CungTinh;
        else
            phamVi = PhamVi.KhacTinh;

        //Debug.Log(tinhNhan.captionText.text);
        Debug.Log(phamVi.ToString());
        CapNhatTongPhi();
    }

    public void ThemSanPham()
    {
        GameObject sanPhamObj = Instantiate(sanPhamPrefab, container);
        sanPhamObj.GetComponentsInChildren<Text>()[0].text = (++count).ToString();
    }
    public void XoaSanPham(GameObject sanPhamXoa)
    {
        DestroyImmediate(sanPhamXoa);
        count--;

        for (int i = 1; i < container.childCount; i++) // ! Bỏ qua Header
        {
            Debug.Log(container.GetChild(i).gameObject.name);
            container.GetChild(i).gameObject.GetComponentsInChildren<Text>()[0].text = i.ToString();
        }
    }

    public void TaoDonNhap()
    {
        if (ValidateInput(taoDonNhap: true))
            StartCoroutine(TaoDonHangHandler(0));
        else
            Manager.instance.Alert("Thiếu thông tin người nhận");
    }

    public void TaoDonHang()
    {
        if (ValidateInput(taoDonNhap: false))
            StartCoroutine(TaoDonHangHandler(1));
        else
            Manager.instance.Alert("Vui lòng nhập đủ thông tin");
    }

    public void CapNhatKhoiLuong()
    {
        int sum = 0;
        foreach (SanPhamMoi sp in container.GetComponentsInChildren<SanPhamMoi>())
        {
            sum += sp.khoiLuong * sp.soLuong;
        }

        tongKhoiLuong = sum;

        tongKL.text = tongKhoiLuong.ToString();

        CapNhatTongPhi();

    }

    void CapNhatTongPhi()
    {
        tongGia = tongKhoiLuong * 10;

        if (phamVi == PhamVi.KhacTinh)
            tongGia += 25000;

        tongGiaText.text = "Tổng phí \n\n" + tongGia.ToString() + " vnđ";
    }


    public void SelectDGN(int index)
    {
        Debug.Log("Select DGN : " + index + " - " + containerDGN.GetChild(index).gameObject.GetComponentInChildren<Toggle>().isOn.ToString());

        if (containerDGN.GetChild(index).gameObject.GetComponentInChildren<Toggle>().isOn == false)
        {
            tenDGN.text = "";
            diachiDGN.text = "";
            coGuiTaiDiemGiaoNhan.isOn = false;
        }
        else
        {
            for (int i = 0; i < containerDGN.childCount; i++)
            {
                if (i == index) continue;

                GameObject dgn = containerDGN.GetChild(i).gameObject;
                dgn.GetComponentInChildren<Toggle>().isOn = false;
            }

            Text[] texts = containerDGN.GetChild(index).gameObject.GetComponentsInChildren<Text>();
            tenDGN.text = texts[0].text;
            diachiDGN.text = texts[1].text;
            coGuiTaiDiemGiaoNhan.isOn = true;
        }


    }

    // Todo: Kiểm tra dữ liệu đã được nhập đủ chưa
    public bool ValidateInput(bool taoDonNhap)
    {
        if (diaChiNhan.text == "")
            return false;

        if (hoTenNhan.text == "")
            return false;

        if (sdtNhan.text == "")
            return false;

        foreach (var sp in container.GetComponentsInChildren<SanPhamMoi>())
        {
            if (sp.tenInput.text == "" || sp.klInput.text == "" || sp.slInput.text == "")
                return false;
        }


        Debug.Log("Validate input : Đơn nháp");
        // * Đơn nháp chỉ cần đủ thông tin người dùng
        if (taoDonNhap == true)
            return true;

        if (coDiaChiTraHang.isOn && diaChiTraHang.text == "")
            return false;

        if (coGuiTaiDiemGiaoNhan.isOn && diachiDGN.text == "")
            return false;

        if (container.childCount == 0)
            return false;


        Debug.Log("Validate input : Đơn real");
        return true;
    }

    // Todo : Lấy danh sách điểm giao nhận
    IEnumerator LayDanhSachDGN()
    {
        WWWForm form = new WWWForm();
        form.AddField("DGN_list", "");

        // * URL
        string url = "http://localhost/php/order.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                for (int i = 1; i < result.Length - 1; i++)
                {
                    // * Data Handler
                    string[] data = result[i].Split('\t');
                    GameObject dgn = Instantiate(diemGiaoNhanPrefab, containerDGN);
                    Text[] texts = dgn.GetComponentsInChildren<Text>();
                    texts[0].text = data[0];
                    texts[1].text = data[1];
                    int index = i - 1;
                    dgn.GetComponentInChildren<Toggle>().onValueChanged.AddListener(delegate
                    {
                        SelectDGN(index);
                    });
                }
                Debug.Log("Lấy danh sách ĐGN thành công");
            }
            else // * Request Fail
            {
                Debug.Log("Lấy danh sách ĐGN thất bại -> Error : " + result[0]);
            }
        }
        isWaiting = false;
    }

    // Todo : Tạo 1 đơn hàng mớI
    IEnumerator TaoDonHangHandler(int trangthai)
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("create_order", "");
        form.AddField("store_id", Manager.instance.cuaHangCrt.Split('-')[1].Remove(0, 1));
        form.AddField("size", 1000);

        if (coDiaChiTraHang.isOn)
            form.AddField("resend_addr", diaChiTraHang.text + " - " + tinhTra.captionText.text);
        else
            form.AddField("resend_addr", "");

        form.AddField("recv_name", hoTenNhan.text);
        form.AddField("recv_phone", sdtNhan.text);
        form.AddField("recv_addr", diaChiNhan.text + " - " + tinhNhan.captionText.text);
        form.AddField("status", trangthai);

        form.AddField("shift", caLayHang.value);

        if (coGuiTaiDiemGiaoNhan.isOn)
            form.AddField("tenDGN", tenDGN.text);
        else
            form.AddField("tenDGN", "");


        // * URL
        string url = "http://localhost/php/order.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                Debug.Log("Tạo đơn hàng thành công -> Mã vận đơn : " + result[1]);
                StartCoroutine(ThemSanPhamMoiHandler(result[1]));
            }
            else // * Request Fail
            {
                Debug.Log("Tạo đơn hàng thất bại -> Error : " + result[0]);
            }
        }
    }

    IEnumerator CapNhatDonNhap(int trangthai)
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("create_order", "");
        form.AddField("store_id", Manager.instance.cuaHangCrt.Split('-')[1].Remove(0, 1));
        form.AddField("size", 1000);

        if (coDiaChiTraHang.isOn)
            form.AddField("resend_addr", diaChiTraHang.text + " - " + tinhTra.captionText.text);
        else
            form.AddField("resend_addr", "");

        form.AddField("recv_name", hoTenNhan.text);
        form.AddField("recv_phone", sdtNhan.text);
        form.AddField("recv_addr", diaChiNhan.text + " - " + tinhNhan.captionText.text);
        form.AddField("status", trangthai);

        form.AddField("shift", caLayHang.value);

        if (coGuiTaiDiemGiaoNhan.isOn)
            form.AddField("tenDGN", tenDGN.text);
        else
            form.AddField("tenDGN", "");


        // * URL
        string url = "http://localhost/php/order.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                Debug.Log("Tạo đơn hàng thành công -> Mã vận đơn : " + result[1]);
                StartCoroutine(ThemSanPhamMoiHandler(result[1]));
            }
            else // * Request Fail
            {
                Debug.Log("Tạo đơn hàng thất bại -> Error : " + result[0]);
            }
        }
    }

    // Todo : Thêm sản phẩm vào đơn hàng 
    IEnumerator ThemSanPhamMoiHandler(string maVanDon)
    {
        for (int i = 0; i < container.childCount; i++)
        {
            SanPhamMoi sanPhamMoi = container.GetChild(i).GetComponent<SanPhamMoi>();
            WWWForm form = new WWWForm();
            form.AddField("add_item", "");
            form.AddField("order_id", maVanDon);
            //form.AddField("stt", i + 1);
            form.AddField("name", sanPhamMoi.tenSanPham);
            form.AddField("weight", sanPhamMoi.khoiLuong);
            form.AddField("quantity", sanPhamMoi.soLuong);

            // * URL
            string url = "http://localhost/php/order.php";

            // * Request handler
            using (UnityWebRequest www = UnityWebRequest.Post(url, form))
            {
                yield return www.SendWebRequest();

                Debug.Log(www.downloadHandler.text);

                string[] result = www.downloadHandler.text.Split('\n');

                // * Request Success
                if (result[0] == "0")
                {
                    Debug.Log("Thêm sản phẩm thành công -> STT : " + (i + 1).ToString());
                }
                else // * Request Fail
                {
                    Debug.Log("Thêm sản phẩm thất bại -> Error : " + result[0]);
                }
            }
        }
    }

    // Todo : Lấy thông tin của đơn nháp
    IEnumerator LayDonHangHandler(string maVanDon)
    {
        yield return new WaitUntil(() => isWaiting == false);

        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("don_nhap", "");
        form.AddField("order_id", maVanDon);

        // * URL
        string url = "http://localhost/php/order.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                // ! Thông tin đơn hàng (result[1])
                {
                    // * Data Handler
                    string[] data = result[1].Split('\t');
                    // * Có điểm trả hàng
                    Debug.Log("data[0] : " + data[0]);
                    if (data[0] != "")
                    {
                        string[] diachi = data[0].Split('-');
                        diaChiTraHang.text = diachi[0].Remove(diachi[0].Length - 1); // Xoá dấu cách cuối và đầu
                        coDiaChiTraHang.isOn = true;
                        tinhTra.value = tinhTra.options.FindIndex(option => option.text == diachi[0].Remove(0, 1));
                    }
                    else
                    {
                        coDiaChiTraHang.isOn = false;
                        diaChiTraHang.text = "";
                        tinhTra.value = 0;
                    }

                    Debug.Log("data[1] : " + data[1]);
                    // * Có điểm giao nhận
                    if (data[1] != " - ")
                    {
                        for (int j = 0; j < containerDGN.childCount; j++)
                        {
                            GameObject dgn = containerDGN.GetChild(j).gameObject;
                            if (dgn.GetComponentsInChildren<Text>()[0].text == data[1])
                            {
                                diachiDGN.text = dgn.GetComponentsInChildren<Text>()[1].text;
                                dgn.GetComponentInChildren<Toggle>().isOn = true;
                            }
                        }
                        coGuiTaiDiemGiaoNhan.isOn = true;
                        diemGiaoNhan.gameObject.SetActive(false);
                    }

                    caLayHang.value = int.Parse(data[2]);

                    hoTenNhan.text = data[3];
                    sdtNhan.text = data[4];
                    string[] temp = data[5].Split('-');
                    // Xoá dấu cách cuối và đầu
                    diaChiNhan.text = temp[0].Remove(temp[0].Length - 1);
                    tinhNhan.value = tinhNhan.options.FindIndex(option => option.text == temp[1].Remove(0, 1));

                }

                // ! Danh sách sản phẩm
                for (int i = 2; i < result.Length - 1; i++)
                {
                    string[] data = result[i].Split('\t');
                    GameObject sanPhamObj = Instantiate(sanPhamPrefab, container);
                    sanPhamObj.GetComponentsInChildren<Text>()[0].text = data[0];
                    SanPhamMoi sanPhamMoi = sanPhamObj.GetComponent<SanPhamMoi>();
                    sanPhamMoi.tenInput.text = data[1];
                    sanPhamMoi.klInput.text = data[2];
                    sanPhamMoi.slInput.text = data[3];
                    sanPhamMoi.CapNhatTen();
                    sanPhamMoi.CapNhatSoLuong();
                    sanPhamMoi.CapNhatKhoiLuong();

                    count++;
                }

                Debug.Log("Lấy thông tin đơn hàng thành công");
            }
            else // * Request Fail
            {
                Debug.Log("Lấy thông tin đơn hàng thất bại -> Error : " + result[0]);
            }
        }
    }

    // Todo : Lấy thông tin cửa hàng hiện tại đang thao tác
    IEnumerator LayThongTinCuaHangHandler()
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("store_info", "");
        form.AddField("store_id", Manager.instance.cuaHangCrt.Split('-')[1].Remove(0, 1));

        // Cua hang A - 12312411
        // * URL
        string url = "http://localhost/php/order.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                for (int i = 1; i < result.Length - 1; i++)
                {
                    // * Data Handler
                    string[] data = result[i].Split('\t');
                    ten_SdtGui.text = data[0] + " - " + data[1];
                    diaChiGui.text = data[2];
                }
                Debug.Log("Lấy thông tin cửa hàng thành công");
            }
            else // * Request Fail
            {
                Debug.Log("Lấy thông tin cửa hàng thất bại -> Error : " + result[0]);
            }
        }
    }
}
