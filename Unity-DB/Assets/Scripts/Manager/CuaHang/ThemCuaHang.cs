using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;
public class ThemCuaHang : MonoBehaviour
{
    public GameObject themPanel;
    public InputField tenCuaHang;
    public InputField sdt;
    public InputField diaChi;
    public Dropdown danhSachTinh;

    public void Load()
    {
        themPanel.SetActive(true);

        tenCuaHang.text = "";
        sdt.text = "";
        diaChi.text = "";

        danhSachTinh.ClearOptions();
        foreach (var tinh in Manager.instance.danhSachTinh)
        {
            danhSachTinh.options.Add(new Dropdown.OptionData(tinh.name));
        }
    }

    public void Them()
    {
        if (ValidateInput())
        {
            StartCoroutine(ThemCuaHangHandler());
        }
        else
        {
            Manager.instance.Alert("Vui lòng nhập đủ thông tin cửa hàng");
        }
    }

    bool ValidateInput()
    {
        if (tenCuaHang.text == "" || sdt.text == "" || diaChi.text == "")
            return false;
        else
            return true;
    }

    IEnumerator ThemCuaHangHandler()
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("new_store", "");
        form.AddField("owner_id", Manager.instance.userId);
        form.AddField("store_name", tenCuaHang.text);
        form.AddField("store_phone", sdt.text);
        form.AddField("store_addr", diaChi.text + " - " + danhSachTinh.captionText.text);

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
                Debug.Log("Thêm cửa hàng thành công");
                Manager.instance.CapNhatCuaHang();
                QuanLyCuaHang.instance.Load();
                themPanel.SetActive(false);
            }
            else // * Request Fail
            {
                Debug.Log("Thêm cửa hàng thất bại -> Error : " + result[0]);
            }
        }
    }
}
