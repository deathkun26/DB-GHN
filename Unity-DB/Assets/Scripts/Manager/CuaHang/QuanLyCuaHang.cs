using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;

public class QuanLyCuaHang : ButtonHandler
{
    public static QuanLyCuaHang instance;
    public GameObject cuaHangPrefab;
    public Dropdown trangthai;
    public Transform container;
    public InputField idNhanVien;

    private void Start()
    {
        if (instance == null)
            instance = this;
    }

    public override void PointerDownHandler()
    {
        Load();
    }

    public void Load()
    {
        Debug.Log("Loading");

        StartCoroutine(LayDanhSachCuaHang());
    }

    public void CapNhatSTT(CuaHang cuaHangXoa)
    {
        StartCoroutine(XoaCuaHang(cuaHangXoa.id.text));
        Destroy(cuaHangXoa.gameObject);
        for (int i = 0; i < container.childCount; i++)
        {
            container.GetChild(i).gameObject.GetComponent<CuaHang>().stt.text = (i + 1).ToString();
        }
    }

    IEnumerator XoaCuaHang(string maCuaHang)
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("delete", "");
        form.AddField("store_id", maCuaHang);

        // * URL
        string url = "http://localhost/php/store.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                Debug.Log("Xoá cửa hàng thành công");
                Manager.instance.CapNhatCuaHang();
            }
            else // * Request Fail
            {
                Debug.Log("Xoá cửa hàng thất bại -> Error : " + result[0]);
            }
        }
    }

    public IEnumerator LayDanhSachCuaHang()
    {
        Manager.ClearContainer(container, clearHeader: true);

        // * Data field
        WWWForm form = new WWWForm();
        if (idNhanVien.text == "")
            form.AddField("filter", "");
        else
        {
            Debug.Log("Filter by id : " + idNhanVien.text);
            form.AddField("filterById", "");
            form.AddField("employee_id", idNhanVien.text);
        }
        form.AddField("status", trangthai.value - 1); // -1 , 0 , 1
        form.AddField("owner_id", Manager.instance.userId);

        // * URL
        string url = "http://localhost/php/store.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');


            // * Request Success
            if (result[0] == "0")
            {
                for (int i = 1; i < result.Length - 1; i++) // ! Bỏ \n cuối
                {
                    //Debug.Log(result[i]);
                    string[] data = result[i].Split('\t');
                    GameObject cuaHangObj = Instantiate(cuaHangPrefab, container);
                    CuaHang cuaHang = cuaHangObj.GetComponent<CuaHang>();
                    cuaHang.stt.text = i.ToString();
                    cuaHang.id.text = data[0];
                    cuaHang.tenCuaHang.text = data[1];
                    if (data[2] == "0")
                        cuaHang.trangThai.text = "Ngưng kích hoạt";
                    else
                        cuaHang.trangThai.text = "Đã kích hoạt";
                    cuaHang.sdt.text = data[3];
                    cuaHang.diaChi.text = data[4];
                }
            }
            else // * Request Fail
            {
                Debug.Log("Lấy danh sách cửa hàng -> Error : " + result[0]);
            }
        }
    }
}
