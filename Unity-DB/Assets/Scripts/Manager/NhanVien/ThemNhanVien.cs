using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Networking;

public class ThemNhanVien : MonoBehaviour
{
    public GameObject form;
    public GameObject tenNv;
    public GameObject sdt;
    public GameObject cuaHangPrefab;
    public InputField id;
    public Text alert;
    public Transform container;

    public void Load()
    {
        Manager.ClearContainer(container, clearHeader: true);

        foreach (var cuaHang in QuanLyNhanVien.instance.dsCuaHang.options)
        {
            if (cuaHang.text == "Tất cả") continue;
            GameObject cuaHangNv = Instantiate(cuaHangPrefab, container);
            cuaHangNv.GetComponentInChildren<Text>().text = cuaHang.text;
        }

        form.SetActive(true);
        tenNv.SetActive(false);
        sdt.SetActive(false);
        id.text = "";
    }

    public void CheckId()
    {
        if (id.text != "")
        {
            StartCoroutine(CheckHandler(id.text));
        }
    }

    public void Them()
    {
        if (tenNv.activeSelf == true)
        {
            bool success = false;
            for (int i = 0; i < container.childCount; i++)
            {
                GameObject cuahang = container.GetChild(i).gameObject;
                if (cuahang.GetComponentInChildren<Toggle>().isOn == true)
                {
                    success = true;
                    StartCoroutine(ThemHandler(id.text, cuahang.GetComponentInChildren<Text>().text.Split('-')[1].Remove(0, 1)));
                }
            }
            if (success == false)
                Manager.instance.Alert("Vui lòng chọn ít nhất 1 cửa hàng");
        }
        else
            Manager.instance.Alert("Vui lòng nhập đúng Id");
    }

    IEnumerator CheckHandler(string id)
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("get_employee", "");
        form.AddField("employee_id", id);

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
                string[] data = result[1].Split('\t');
                tenNv.SetActive(true);
                tenNv.GetComponent<Text>().text = data[0];
                sdt.SetActive(true);
                sdt.GetComponent<Text>().text = data[1];
                alert.text = "";
            }
            else if (result[0] == "1")
            {
                alert.text = "Id nhân viên không tồn tại";
                tenNv.SetActive(false);
                sdt.SetActive(false);
            }
            // else if (result[0] == "2")
            // {
            //     alert.text = "Đã là nhân viên trong cửa hàng của bạn";
            //     tenNv.SetActive(true);
            //     sdt.SetActive(true);
            // }
            else // * Request fail
            {
                Debug.Log("Request error : " + result[0]);
            }
        }
    }

    IEnumerator ThemHandler(string id, string maCH)
    {
        // * Data field
        WWWForm form = new WWWForm();
        form.AddField("add_employee", "");
        form.AddField("employee_id", id);
        form.AddField("store_id", maCH);

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
                QuanLyNhanVien.instance.CapNhatNhanVien();
                this.form.SetActive(false);
                Debug.Log("Thêm nhân viên thành công");
            }
            else // * Request fail
            {
                Debug.Log("Thêm nhân viên thất bại : " + result[0]);
            }
        }
    }
}
