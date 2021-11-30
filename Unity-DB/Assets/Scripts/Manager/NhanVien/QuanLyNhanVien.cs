using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;

public class QuanLyNhanVien : ButtonHandler
{
    public static QuanLyNhanVien instance;
    public Transform container;
    public GameObject nhanVienPrefab;
    public Dropdown dsCuaHang;


    private void Start()
    {
        if (instance == null)
            instance = this;
    }

    public override void PointerDownHandler()
    {
        dsCuaHang.ClearOptions();
        dsCuaHang.options.Add(new Dropdown.OptionData("Tất cả"));
        dsCuaHang.value = 0;
        dsCuaHang.captionText.text = "Tất cả";
        StartCoroutine(LayDanhSachCH());
        Load();
    }

    public void Load()
    {
        Debug.Log("Loading");

        StartCoroutine(DShNhanVienHandler());
    }

    public void XoaNhanVien(NhanVien nv)
    {
        StartCoroutine(XoaNVHandler(nv.id.text, nv.cuahang.text));
        Destroy(nv.gameObject);
        for (int i = 0; i < container.childCount; i++)
        {
            container.GetChild(i).gameObject.GetComponent<NhanVien>().stt.text = (i + 1).ToString();
        }
    }

    public void CapNhatNhanVien()
    {
        StartCoroutine(DShNhanVienHandler());
    }

    IEnumerator XoaNVHandler(string employee_id, string store_id)
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("delete", "");
        form.AddField("store_id", store_id);
        form.AddField("employee_id", employee_id);

        // * URL
        string url = "http://localhost/php/employee.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                Debug.Log("Xoá nhân viên thành công");
            }
            else // * Request Fail
            {
                Debug.Log("Xoá nhân viên thất bại -> Error : " + result[0]);
            }
        }
    }
    IEnumerator LayDanhSachCH()
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("all_store_list", "");
        form.AddField("owner_id", Manager.instance.userId);

        // * URL
        string url = "http://localhost/php/employee.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');

            // * Request Success
            if (result[0] == "0")
            {
                for (int i = 1; i < result.Length - 1; i++)
                {
                    dsCuaHang.options.Add(new Dropdown.OptionData(result[i]));
                }
                Debug.Log("Lấy danh sách cửa hàng thành công");
            }
            else // * Request Fail
            {
                Debug.Log("Lấy danh sách cửa hàng thất bại -> Error : " + result[0]);
            }
        }
    }
    public IEnumerator DShNhanVienHandler()
    {
        Manager.ClearContainer(container, clearHeader: true);
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("employee_filter", 1);

        string store_id = "";
        if (dsCuaHang.value != 0)
            store_id = dsCuaHang.captionText.text.Split('-')[1].Remove(0, 1);
        Debug.Log("_" + store_id + "_");
        form.AddField("store_id", store_id);
        form.AddField("owner_id", Manager.instance.userId);

        // * URL
        string url = "http://localhost/php/employee.php";

        // * Request handler
        using (UnityWebRequest www = UnityWebRequest.Post(url, form))
        {
            yield return www.SendWebRequest();

            //Debug.Log(www.downloadHandler.text);

            string[] result = www.downloadHandler.text.Split('\n');


            // * Request Success
            if (result[0] == "0")
            {
                for (int i = 1; i < result.Length - 1; i++) // ! Bỏ \n cuối
                {
                    //Debug.Log(result[i]);
                    string[] data = result[i].Split('\t');
                    GameObject nvObj = Instantiate(nhanVienPrefab, container);
                    NhanVien nv = nvObj.GetComponent<NhanVien>();
                    nv.stt.text = i.ToString();
                    nv.id.text = data[0];
                    nv.ten.text = data[1];
                    nv.sdt.text = data[2];
                    nv.cuahang.text = string.Join("\n", data[3].Split('-'));
                }
            }
            else // * Request Fail
            {
                Debug.Log("Lấy danh sách nhân viên -> Error : " + result[0]);
            }
        }
    }
}